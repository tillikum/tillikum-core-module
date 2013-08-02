<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Facility;

use DateTime;
use Doctrine\ORM\EntityManager;
use Tillikum\Common\Occupancy\Engine as OccupancyEngine;
use Tillikum\Common\Occupancy\Input as OccupancyInput;
use Tillikum\ORM\EntityManagerAwareInterface;

class Hold extends \Tillikum_Form implements EntityManagerAwareInterface
{
    public $entity;

    protected $em;

    public function bind($entity)
    {
        $this->entity = $entity;

        $this->id->setValue($entity->id);
        $this->facility_id->setValue($entity->facility->id);
        $this->start->setValue($entity->start ? $entity->start->format('Y-m-d') : '');
        $this->end->setValue($entity->end ? $entity->end->format('Y-m-d') : '');
        $this->description->setValue($entity->description);
        $this->gender->setValue($entity->gender);
        $this->space->setValue($entity->space);

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->entity)) {
            return $this;
        }

        $this->entity->start = new DateTime($this->start->getValue());
        $this->entity->end = new DateTime($this->end->getValue());
        $this->entity->description = $this->description->getValue();
        $this->entity->gender = $this->gender->getValue();
        $this->entity->space = $this->space->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $id = new \Zend_Form_Element_Hidden(
            'id',
            array(
                'decorators' => array(
                    'ViewHelper'
                )
            )
        );

        $facilityId = new \Zend_Form_Element_Hidden(
            'facility_id',
            array(
                'decorators' => array(
                    'ViewHelper'
                )
            )
        );

        $start = new \Tillikum_Form_Element_Date(
            'start',
            array(
                'label' => 'Start date',
                'required' => true
            )
        );

        $end = new \Tillikum_Form_Element_Date(
            'end',
            array(
                'label' => 'End date',
                'required' => true
            )
        );

        $description = new \Zend_Form_Element_Text(
            'description',
            array(
                'label' => 'Description',
                'filters' => array(
                    'StringTrim'
                )
            )
        );

        $space = new \Tillikum_Form_Element_Number(
            'space',
            array(
                'attribs' => array(
                    'min' => 0
                ),
                'filters' => array(
                    'Int'
                ),
                'label' => 'Spaces to hold',
                'required' => true,
                'validators' => array(
                    new \Zend_Validate_GreaterThan(0)
                )
            )
        );

        $gender = new \Zend_Form_Element_Select(
            'gender',
            array(
                'label' => 'Gender of hold',
                'multiOptions' => array(
                    'F' => 'Female',
                    'M' => 'Male',
                    'U' => 'Ungendered'
                ),
                'value' => 'U'
            )
        );

        $this->addElements(array(
            $id,
            $facilityId,
            $start,
            $end,
            $space,
            $gender,
            $description,
            $this->createSubmitElement(array('label' => 'Save'))
        ));
    }

    public function isValid($data)
    {
        if (!parent::isValid($data)) {
            return false;
        }

        $startDate = new DateTime($data['start']);
        $endDate = new DateTime($data['end']);

        if ($startDate > $endDate) {
            $this->start->addError(
                $this->getTranslator()->translate(
                    'The start date must be on or before the end date.'
                )
            );

            $this->end->addError(
                $this->getTranslator()->translate(
                    'The end date must be on or after the start date.'
                )
            );

            return false;
        }

        $facility = $this->em->find(
            'Tillikum\Entity\Facility\Facility',
            $data['facility_id']
        );

        $holdQueryBuilder = $this->em->createQueryBuilder()
            ->select('h')
            ->from('Tillikum\Entity\Facility\Hold\Hold', 'h')
            ->where('h.start <= :proposedEnd')
            ->andWhere('h.end >= :proposedStart')
            ->andWhere('h.facility = :facility')
            ->orderBy('h.start')
            ->setParameter('facility', $facility)
            ->setParameter('proposedStart', $startDate)
            ->setParameter('proposedEnd', $endDate);

        if ($this->entity && isset($this->entity->id)) {
            $holdQueryBuilder->andWhere('h != :entity')
                ->setParameter('entity', $this->entity);

        }

        $holds = $holdQueryBuilder
            ->getQuery()
            ->getResult();

        $bookings = $this->em->createQueryBuilder()
            ->select('b')
            ->from('Tillikum\Entity\Booking\Facility\Facility', 'b')
            ->where('b.start <= :proposedEnd')
            ->andWhere('b.end >= :proposedStart')
            ->andWhere('b.facility = :facility')
            ->orderBy('b.start')
            ->setParameter('facility', $facility)
            ->setParameter('proposedStart', $startDate)
            ->setParameter('proposedEnd', $endDate)
            ->getQuery()
            ->getResult();

        $configs = $this->em->createQueryBuilder()
            ->select('c')
            ->from('Tillikum\Entity\Facility\Config\Config', 'c')
            ->where('c.facility = :facility')
            ->orderBy('c.start')
            ->setParameter('facility', $facility)
            ->getQuery()
            ->getResult();

        if (count($holds) > 0) {
            foreach ($holds as $hold) {
                $errorMessage = sprintf(
                    $this->getTranslator()->translate(
                        'An existing hold from %s to %s overlaps your intended hold.'
                    ),
                    $hold->start->format('Y-m-d'),
                    $hold->end->format('Y-m-d')
                );

                $this->start->addError($errorMessage);
                $this->end->addError($errorMessage);
            }

            return false;
        }

        $occupancyInputs = array(
            new OccupancyInput(
                $startDate,
                $data['space'] * -1,
                sprintf(
                    'start of the hold you specified from %s to %s',
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                )
            ),
            new OccupancyInput(
                date_modify(clone $endDate, '+1 day'),
                $data['space'],
                sprintf(
                    'end of the hold you specified from %s to %s',
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                )
            ),
        );

        foreach ($bookings as $booking) {
            $occupancyInputs[] = new OccupancyInput(
                $booking->start,
                -1,
                sprintf(
                    'start of a booking from %s to %s',
                    $booking->start->format('Y-m-d'),
                    $booking->end->format('Y-m-d')
                )
            );

            $occupancyInputs[] = new OccupancyInput(
                date_modify(clone $booking->end, '+1 day'),
                1,
                sprintf(
                    'end of a booking from %s to %s',
                    $booking->start->format('Y-m-d'),
                    $booking->end->format('Y-m-d')
                )
            );
        }

        $currentConfigSpace = 0;
        foreach ($configs as $config) {
            $occupancyInputs[] = new OccupancyInput(
                $config->start,
                // Change in space = number of new spaces available or taken,
                // assumes configs are sorted
                $config->capacity - $currentConfigSpace,
                sprintf(
                    'start of a facility configuration from %s to %s',
                    $config->start->format('Y-m-d'),
                    $config->end->format('Y-m-d')
                )
            );

            $currentConfigSpace = $config->capacity;
        }

        foreach ($holds as $hold) {
            $occupancyInputs[] = new OccupancyInput(
                $hold->start,
                $hold->space * -1,
                sprintf(
                    'start of a hold from %s to %s',
                    $hold->start->format('Y-m-d'),
                    $hold->end->format('Y-m-d')
                )
            );

            $occupancyInputs[] = new OccupancyInput(
                date_modify(clone $hold->end, '+1 day'),
                $hold->space,
                sprintf(
                    'end of a hold from %s to %s',
                    $hold->start->format('Y-m-d'),
                    $hold->end->format('Y-m-d')
                )
            );
        }

        $occupancyEngine = new OccupancyEngine($occupancyInputs);

        $occupancyResult = $occupancyEngine->run();

        if (!$occupancyResult->getIsSuccess()) {
            $errorMessage = sprintf(
                $this->getTranslator()->translate(
                    'There is no available space in this facility to add another ' .
                    'hold during the specified time period. The problem occurred ' .
                    'at the %s.'
                ),
                $occupancyResult->getCulprit()->getDescription()
            );

            $this->start->addError($errorMessage);
            $this->end->addError($errorMessage);

            return false;
        }

        return true;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        return $this;
    }
}
