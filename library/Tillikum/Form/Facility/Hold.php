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

class Hold extends \Tillikum_Form
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
            ->setParameter('facility', $facility)
            ->setParameter('proposedStart', $startDate)
            ->setParameter('proposedEnd', $endDate)
            ->getQuery()
            ->getResult();

        $configs = $this->em->createQueryBuilder()
            ->select('c')
            ->from('Tillikum\Entity\Facility\Config\Config', 'c')
            ->where('c.start <= :proposedEnd')
            ->andWhere('c.end >= :proposedStart')
            ->andWhere('c.facility = :facility')
            ->setParameter('facility', $facility)
            ->setParameter('proposedStart', $startDate)
            ->setParameter('proposedEnd', $endDate)
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

        $configCapacity = PHP_INT_MAX;
        foreach ($configs as $config) {
            $configCapacity = min($configCapacity, $config->capacity);
        }

        $moments = array();
        foreach ($bookings as $booking) {
            $moments[] = array(
                'date' => $booking->start,
                'value' => 1
            );
            $moments[] = array(
                'date' => $booking->end,
                'value' => -1
            );
        }

        foreach ($holds as $hold) {
            $moments[] = array(
                'date' => $hold->start,
                'value' => $hold->space,
            );
            $moments[] = array(
                'date' => $hold->end,
                'value' => $hold->space * -1,
            );
        }

        $customSort = function ($a, $b) {
            if ($a['date'] == $b['date']) {
                if ($a['value'] == $b['value']) {
                    return 0;
                }

                // Positive values come first, so same-day start/ends are
                // incremented before they are decremented
                return $a['value'] > $b['value'] ? -1 : 1;
            }

            return $a['date'] < $b['date'] ? -1 : 1;
        };

        usort($moments, $customSort);

        $currentCount = 0;
        $highestCount = 0;
        $highestStart = null;
        foreach ($moments as $moment) {
            $currentCount += $moment['value'];

            if ($currentCount > $highestCount) {
                $highestStart = $moment['date'];
                $highestCount = $currentCount;
            }
        }

        if ($highestCount >= $configCapacity) {
            $errorMessage = sprintf(
                $this->getTranslator()->translate(
                    'There is no available space in this facility to add another ' .
                    'hold during the specified time period. The minimum ' .
                    'configured space is %s, but there are %s bookings ' .
                    'and/or held spaces starting on %s.'
                ),
                $configCapacity,
                $highestCount,
                $highestStart ? $highestStart->format('Y-m-d') : '[no date]'
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
