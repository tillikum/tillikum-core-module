<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Booking;

use DateTime;
use Doctrine\ORM\EntityManager;
use Tillikum\Common\Occupancy\Engine as OccupancyEngine;
use Tillikum\Common\Occupancy\Input as OccupancyInput;
use Tillikum\ORM\EntityManagerAwareInterface;
use Tillikum\Specification\Specification\GenderMatch as GenderMatchSpecification;
use Vo\DateRange;

class Facility extends \Tillikum_Form implements EntityManagerAwareInterface
{
    public $booking;

    protected $em;

    public function bind($booking)
    {
        $this->booking = $booking;

        $this->id->setValue($booking->id);
        $this->facility_id->setValue($booking->facility ? $booking->facility->id : '');

        if ($booking->facility) {
            $this->facility_name->setValue(
                implode(' ', $booking->facility->getNamesOnDate($booking->start))
            );
        }

        $this->start->setValue($booking->start ? $booking->start->format('Y-m-d') : '');
        $this->end->setValue($booking->end ? $booking->end->format('Y-m-d') : '');
        $this->checkin_at->setValue($booking->checkin_at ? date('c', $booking->checkin_at->format('U')) : '');
        $this->checkout_at->setValue($booking->checkout_at ? date('c', $booking->checkout_at->format('U')) : '');
        $this->note->setValue($booking->note);

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->booking)) {
            return;
        }

        $this->booking->facility = $this->em->find(
            'Tillikum\Entity\Facility\Facility',
            $this->facility_id->getValue()
        );
        $this->booking->start = new DateTime($this->start->getValue());
        $this->booking->end = new DateTime($this->end->getValue());
        $this->booking->checkin_at = $this->checkin_at->getValue() ? new DateTime($this->checkin_at->getValue()) : null;
        $this->booking->checkout_at = $this->checkout_at->getValue() ? new DateTime($this->checkout_at->getValue()) : null;
        $this->booking->note = $this->note->getValue();

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
                ),
                'required' => true
            )
        );

        $facilityName = new \Zend_Form_Element_Text(
            'facility_name',
            array(
                'ignore' => true,
                'label' => 'Facility name'
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

        $checkinAt = new \Tillikum_Form_Element_Datetime(
            'checkin_at',
            array(
                'label' => 'Check-in date and time'
            )
        );

        $checkoutAt = new \Tillikum_Form_Element_Datetime(
            'checkout_at',
            array(
                'label' => 'Check-out date and time'
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

        $this->addElements(
            array(
                $id,
                $facilityId,
                $facilityName,
                $start,
                $end,
                $checkinAt,
                $checkoutAt,
                $note,
                $this->createSubmitElement(array('label' => 'Next...')),
            )
        );
    }

    public function isValid($data)
    {
        if (!parent::isValid($data)) {
            return false;
        }

        if ($this->isArray()) {
            $data = $this->_dissolveArrayValue(
                $data,
                $this->getElementsBelongTo()
            );
        }

        $startDate = new DateTime($data['start']);
        $endDate = new DateTime($data['end']);

        if ($startDate > $endDate) {
            $this->start->addError(
                $this->getTranslator()->translate(
                    'The start date must be on or before the end date.'
            ));

            $this->end->addError(
                $this->getTranslator()->translate(
                    'The end date must be on or after the start date.'
                )
            );

            return false;
        }

        $bookingRange = new DateRange($startDate, $endDate);
        $bookingFacility = $this->em->find(
            'Tillikum\Entity\Facility\Facility',
            $data['facility_id']
        );

        $person = $this->booking->person;

        $bookings = $this->em->createQueryBuilder()
            ->select('b')
            ->from('Tillikum\Entity\Booking\Facility\Facility', 'b')
            ->where('b.start <= :proposedEnd')
            ->andWhere('b.end >= :proposedStart')
            ->andWhere('b.facility = :facility')
            ->andWhere('b.person != :person')
            ->orderBy('b.start')
            ->setParameter('facility', $bookingFacility)
            ->setParameter('person', $person)
            ->setParameter('proposedStart', $bookingRange->getStart())
            ->setParameter('proposedEnd', $bookingRange->getEnd())
            ->getQuery()
            ->getResult();

        $configs = $this->em->createQueryBuilder()
            ->select('c')
            ->from('Tillikum\Entity\Facility\Config\Config', 'c')
            ->where('c.facility = :facility')
            ->orderBy('c.start')
            ->setParameter('facility', $bookingFacility)
            ->getQuery()
            ->getResult();

        $holds = $this->em->createQueryBuilder()
            ->select('h')
            ->from('Tillikum\Entity\Facility\Hold\Hold', 'h')
            ->where('h.start <= :proposedEnd')
            ->andWhere('h.end >= :proposedStart')
            ->andWhere('h.facility = :facility')
            ->orderBy('h.start')
            ->setParameter('facility', $bookingFacility)
            ->setParameter('proposedStart', $bookingRange->getStart())
            ->setParameter('proposedEnd', $bookingRange->getEnd())
            ->getQuery()
            ->getResult();

        $occupancyInputs = array(
            new OccupancyInput(
                $bookingRange->getStart(),
                -1,
                sprintf(
                    'start of the booking range you specified from %s to %s',
                    $bookingRange->getStart()->format('Y-m-d'),
                    $bookingRange->getEnd()->format('Y-m-d')
                )
            ),
            new OccupancyInput(
                date_modify(clone $bookingRange->getEnd(), '+1 day'),
                1,
                sprintf(
                    'end of the booking range you specified from %s to %s',
                    $bookingRange->getStart()->format('Y-m-d'),
                    $bookingRange->getEnd()->format('Y-m-d')
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

            if (!empty($booking->person->gender)) {
                $bookingGenderSpec = isset($bookingGenderSpec)
                    ? $bookingGenderSpec->andSpec(new GenderMatchSpecification($booking->person->gender))
                    : new GenderMatchSpecification($booking->person->gender);
            }
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

            if (!empty($config->gender)) {
                $facilityGenderSpec = isset($facilityGenderSpec)
                    ? $facilityGenderSpec->andSpec(new GenderMatchSpecification($config->gender))
                    : new GenderMatchSpecification($config->gender);
            }

            if (!empty($config->suite)) {
                $suiteConfigs = $this->em->createQueryBuilder()
                    ->select('c')
                    ->from('Tillikum\Entity\Facility\Config\Room\Room', 'c')
                    ->where('c.start <= :proposedEnd')
                    ->andWhere('c.end >= :proposedStart')
                    ->andWhere('c.suite = :suite')
                    ->setParameter('proposedStart', $bookingRange->getStart())
                    ->setParameter('proposedEnd', $bookingRange->getEnd())
                    ->setParameter('suite', $config->suite)
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
            $this->facility_name->addError(
                sprintf(
                    $this->getTranslator()->translate(
                        'There is no available space in this facility to book another'
                      . ' resident during the specified time period. The problem'
                      . ' occurred at the %s.'
                    ),
                    $occupancyResult->getCulprit()->getDescription()
                )
            );

            return false;
        }

        if (isset($bookingGenderSpec) && !$bookingGenderSpec->isSatisfiedBy($person->gender)) {
            $this->addWarning(
                sprintf(
                    $this->getTranslator()->translate(
                        'The person you are booking with gender %s did not meet' .
                        ' the gender requirements of the other people booked' .
                        ' to this facility for the desired time period.'
                    ),
                    $person->gender
                )
            );
        }

        if (isset($facilityGenderSpec) && !$facilityGenderSpec->isSatisfiedBy($person->gender)) {
            $this->addWarning(
                sprintf(
                    $this->getTranslator()->translate(
                        'The person you are booking with gender %s did not meet' .
                        ' the gender requirements of the configurations for this' .
                        ' facility for the desired time period.'
                    ),
                    $person->gender
                )
            );
        }

        if (isset($holdGenderSpec) && !$holdGenderSpec->isSatisfiedBy($person->gender)) {
            $this->addWarning(
                sprintf(
                    $this->getTranslator()->translate(
                        'The person you are booking with gender %s did not meet' .
                        ' the gender requirements of the holds on this facility' .
                        ' for the desired time period.'
                    ),
                    $person->gender
                )
            );
        }

        if (isset($suiteGenderSpec) && !$suiteGenderSpec->isSatisfiedBy($person->gender)) {
            $this->addWarning(
                sprintf(
                    $this->getTranslator()->translate(
                        'The person you are booking with gender %s did not meet' .
                        ' the gender requirements of the other people booked to' .
                        ' this suite for the specified time period.'
                    ),
                    $person->gender
                )
            );
        }

        return true;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        return $this;
    }
}
