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
use Tillikum\Specification\Specification\GenderMatch as GenderMatchSpecification;
use Vo\DateRange;

class Config extends \Tillikum_Form implements EntityManagerAwareInterface
{
    protected $em;

    public $entity;

    public function bind($entity)
    {
        $this->entity = $entity;

        $this->id->setValue($entity->id);
        $this->facility_id->setValue($entity->facility->id);
        $this->start->setValue($entity->start ? $entity->start->format('Y-m-d') : '');
        $this->end->setValue($entity->end ? $entity->end->format('Y-m-d') : '');
        $this->name->setValue($entity->name);
        $this->gender->setValue($entity->gender);
        $this->capacity->setValue($entity->capacity);
        $this->note->setValue($entity->note);
        $this->default_billing_rule->setValue($entity->default_billing_rule ? $entity->default_billing_rule->id : '');

        if (count($entity->tags) > 0) {
            $tagValues = array();
            foreach ($entity->tags as $tag) {
                $tagValues[] = $tag->id;
            }
            $this->tags->setValue($tagValues);
        }

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->entity)) {
            return;
        }

        $this->entity->start = new DateTime($this->start->getValue());
        $this->entity->end = new DateTime($this->end->getValue());
        $this->entity->name = $this->name->getValue();
        $this->entity->gender = $this->gender->getValue();
        $this->entity->capacity = $this->capacity->getValue();
        $this->entity->note = $this->note->getValue();
        $this->entity->default_billing_rule = $this->em->find('Tillikum\Entity\Billing\Rule\FacilityBooking', $this->default_billing_rule->getValue());
        $this->entity->tags = $this->em->getRepository('Tillikum\Entity\Facility\Config\Tag')->findById($this->tags->getValue());

        return $this;
    }

    public function init()
    {
        parent::init();

        $id = new \Zend_Form_Element_Hidden(
            'id',
            array(
                'decorators' => array(
                    'ViewHelper',
                )
            )
        );

        $facilityId = new \Zend_Form_Element_Hidden(
            'facility_id',
            array(
                'decorators' => array(
                    'ViewHelper',
                )
            )
        );

        $start = new \Tillikum_Form_Element_Date(
            'start',
            array(
                'label' => 'Start date',
                'required' => true,
            )
        );

        $end = new \Tillikum_Form_Element_Date(
            'end',
            array(
                'label' => 'End date',
                'required' => true,
            )
        );

        $name = new \Zend_Form_Element_Text(
            'name',
            array(
                'label' => 'Name',
                'required' => true,
                'filters' => array(
                    'StringTrim',
                ),
            )
        );

        $gender = new \Zend_Form_Element_Select(
            'gender',
            array(
                'label' => 'Gender',
                'required' => true,
                'multiOptions' => array(
                    'F' => 'Female',
                    'M' => 'Male',
                    'U' => 'Ungendered',
                ),
                'value' => 'U',
            )
        );

        $capacity = new \Tillikum_Form_Element_Number(
            'capacity',
            array(
                'attribs' => array(
                    'min' => 0
                ),
                'label' => 'Capacity',
                'required' => true,
                'filters' => array(
                    'Int',
                ),
                'validators' => array(
                    new \Zend_Validate_GreaterThan(0),
                ),
            )
        );

        $note = new \Zend_Form_Element_Textarea(
            'note',
            array(
                'attribs' => array(
                    'class' => 'short',
                ),
                'label' => 'Notes',
            )
        );

        $defaultBillingRule = new \Zend_Form_Element_Select(
            'default_billing_rule',
            array(
                'label' => 'Default billing rule',
            )
        );

        $tags = new \Zend_Form_Element_Multiselect(
            'tags',
            array(
                'label' => 'Tags',
            )
        );

        $this->addElements(
            array(
                $id,
                $facilityId,
                $start,
                $end,
                $name,
                $gender,
                $capacity,
                $note,
                $defaultBillingRule,
                $tags,
                $this->createSubmitElement(
                    array(
                        'label' => 'Save',
                    )
                ),
            )
        );
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

        $configRange = new DateRange($startDate, $endDate);

        $facility = $this->em->find('Tillikum\Entity\Facility\Facility', $data['facility_id']);

        $bookings = $this->em->createQueryBuilder()
            ->select('b')
            ->from('Tillikum\Entity\Booking\Facility\Facility', 'b')
            ->where('b.start <= :proposedEnd')
            ->andWhere('b.end >= :proposedStart')
            ->andWhere('b.facility = :facility')
            ->orderBy('b.start')
            ->setParameter('facility', $facility)
            ->setParameter('proposedStart', $configRange->getStart())
            ->setParameter('proposedEnd', $configRange->getEnd())
            ->getQuery()
            ->getResult();

        $qb = $this->em->createQueryBuilder()
            ->select('c')
            ->from('Tillikum\Entity\Facility\Config\Config', 'c')
            ->where('c.facility = :facility')
            ->orderBy('c.start')
            ->setParameter('facility', $this->entity->facility);

        if ($this->entity && isset($this->entity->id)) {
            $qb->andWhere('c != :entity')
                ->setParameter('entity', $this->entity);
        }

        $configs = $qb->getQuery()
            ->getResult();

        $overlappingQueryBuilder = $this->em->createQueryBuilder()
            ->select('c')
            ->from('Tillikum\Entity\Facility\Config\Config', 'c')
            ->where('c.start <= :proposedStart')
            ->andWhere('c.end >= :proposedEnd')
            ->andWhere('c.facility = :facility')
            ->setParameter('proposedStart', $configRange->getStart())
            ->setParameter('proposedEnd', $configRange->getEnd())
            ->setParameter('facility', $this->entity->facility);

        if ($this->entity && isset($this->entity->id)) {
            $overlappingQueryBuilder->andWhere('c != :entity')
                ->setParameter('entity', $this->entity);
        }

        $overlappingConfigs = $overlappingQueryBuilder
            ->getQuery()
            ->getResult();

        $holds = $this->em->createQueryBuilder()
            ->select('h')
            ->from('Tillikum\Entity\Facility\Hold\Hold', 'h')
            ->where('h.start <= :proposedEnd')
            ->andWhere('h.end >= :proposedStart')
            ->andWhere('h.facility = :facility')
            ->orderBy('h.start')
            ->setParameter('facility', $facility)
            ->setParameter('proposedStart', $configRange->getStart())
            ->setParameter('proposedEnd', $configRange->getEnd())
            ->getQuery()
            ->getResult();

        if (count($overlappingConfigs) > 0) {
            foreach ($overlappingConfigs as $config) {
                $errorMessage = sprintf(
                    $this->getTranslator()->translate(
                        'An existing configuration from %s to %s overlaps your intended configuration.'
                    ),
                    $config->start->format('Y-m-d'),
                    $config->end->format('Y-m-d')
                );

                $this->start->addError($errorMessage);
                $this->end->addError($errorMessage);
            }

            return false;
        }

        $occupancyInputs = array(
            new OccupancyInput(
                $configRange->getStart(),
                $data['capacity'],
                sprintf(
                    'start of the facility configuration you specified from %s to %s',
                    $configRange->getStart()->format('Y-m-d'),
                    $configRange->getEnd()->format('Y-m-d')
                )
            ),
        );

        foreach ($configs as $config) {
            $occupancyInputs[] = new OccupancyInput(
                $config->start,
                $config->capacity,
                sprintf(
                    'start of a facility configuration from %s to %s',
                    $config->start->format('Y-m-d'),
                    $config->end->format('Y-m-d')
                )
            );

            if (!empty($data['suite'])) {
                $suiteConfigs = $this->em->createQueryBuilder()
                    ->select('c')
                    ->from('Tillikum\Entity\Facility\Config\Room\Room', 'c')
                    ->join('c.suite', 's')
                    ->where('c.start <= :proposedEnd')
                    ->andWhere('c.end >= :proposedStart')
                    ->andWhere('s.id = :suiteId')
                    ->setParameter('proposedStart', $configRange->getStart())
                    ->setParameter('proposedEnd', $configRange->getEnd())
                    ->setParameter('suiteId', $data['suite'])
                    ->getQuery()
                    ->getResult();

                foreach ($suiteConfigs as $suiteConfig) {
                    if (!empty($suiteConfig->gender)) {
                        $suiteGenderSpec = isset($suiteGenderSpec)
                            ? $suiteGenderSpec->andSpec(new GenderMatchSpecification($suiteConfig->gender))
                            : new GenderMatchSpecification($suiteConfig->gender);
                    }
                }
            }
        }

        // We need to re-sort since the user-specified input will not be sorted
        usort(
            $occupancyInputs,
            function($a, $b) {
                if ($a->getDate() == $b->getDate()) {
                    return 0;
                }

                return $a->getDate() < $b->getDate() ? -1 : 1;
            }
        );

        // Actually calculate moments after re-sorting
        $currentConfigSpace = 0;
        foreach ($occupancyInputs as $idx => $input) {
            $oldValue = $input->getValue();

            $occupancyInputs[$idx] = new OccupancyInput(
                $input->getDate(),
                // Change in space = number of new spaces available or taken,
                // assumes configs are sorted
                $input->getValue() - $currentConfigSpace,
                $input->getDescription()
            );

            $currentConfigSpace = $oldValue;
        }

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

            if (!empty($booking->person->gender)) {
                $bookingGenderSpec = isset($bookingGenderSpec)
                    ? $bookingGenderSpec->andSpec(new GenderMatchSpecification($booking->person->gender))
                    : new GenderMatchSpecification($booking->person->gender);
            }
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

            if (!empty($hold->gender)) {
                $holdGenderSpec = isset($holdGenderSpec)
                    ? $holdGenderSpec->andSpec(new GenderMatchSpecification($hold->gender))
                    : new GenderMatchSpecification($hold->gender);
            }
        }

        $occupancyEngine = new OccupancyEngine($occupancyInputs);

        $occupancyResult = $occupancyEngine->run();

        if (!$occupancyResult->getIsSuccess()) {
            $this->capacity->addError(
                sprintf(
                    $this->getTranslator()->translate(
                        'There are too many claims on space in this facility ' .
                        'to change the capacity of this configuration. The ' .
                        'problem occurred at the %s.'
                    ),
                    $occupancyResult->getCulprit()->getDescription()
                )
            );

            return false;
        }

        if (isset($holdGenderSpec) && !$holdGenderSpec->isSatisfiedBy($data['gender'])) {
            $this->addWarning(
                sprintf(
                    $this->getTranslator()->translate(
                        'The desired configuration gender does not meet the' .
                        ' gender requirements of an overlapping facility hold.'
                   )
               )
           );
        }

        if (isset($bookingGenderSpec) && !$bookingGenderSpec->isSatisfiedBy($data['gender'])) {
            $this->addWarning(
                sprintf(
                    $this->getTranslator()->translate(
                        'The desired configuration gender does not meet the' .
                        ' gender requirements of an overlapping booking.'
                    )
                )
            );
        }

        if (isset($suiteGenderSpec) && !$suiteGenderSpec->isSatisfiedBy($data['gender'])) {
            $this->addWarning(
                sprintf(
                    $this->getTranslator()->translate(
                        'The desired configuration gender does not meet the' .
                        ' gender requirements of an overlapping booking in a' .
                        ' related suite.'
                    )
                )
            );
        }

        return true;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        $q = $this->em->createQuery(
            "
            SELECT r.id, r.description
            FROM Tillikum\Entity\Billing\Rule\FacilityBooking r
            ORDER BY r.description
            "
        );

        $defaultBillingRules = array('' => '');
        foreach ($q->getResult() as $row) {
            $defaultBillingRules[$row['id']] = $row['description'];
        }

        $this->default_billing_rule->setMultiOptions($defaultBillingRules);

        $q = $this->em->createQuery(
            "
            SELECT t.id, t.name
            FROM Tillikum\Entity\Facility\Config\Tag t
            ORDER BY t.name
            "
        );

        $tags = array();
        foreach ($q->getResult() as $row) {
            $tags[$row['id']] = $row['name'];
        }

        $this->tags->setMultiOptions($tags);
        $this->tags->setAttrib('size', min(10, count($tags)));

        return $this;
    }
}
