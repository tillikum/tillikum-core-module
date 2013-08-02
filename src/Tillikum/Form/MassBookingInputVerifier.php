<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

class Tillikum_Form_MassBookingInputVerifier extends Tillikum_Form_MassInputVerifier
{
    protected $data;

    public function getData()
    {
        return $this->data;
    }

    public function checkMappedData($map, $rows)
    {
        $personGateway = new \TillikumX\Model\PersonGateway();

        $identity = \Zend_Auth::getInstance()->hasIdentity()
            ? Zend_Auth::getInstance()->getIdentity()
            : '_system';

        $rowNum = 1;
        $this->data = $rangesByFacility = array();
        foreach ($rows as $row) {
            $person = $personGateway->fetch($row[$map['id']]);

            if (null === $person) {
                return array(
                    'result' => false,
                    'reason' => sprintf(
                        'The person with ID %s does not exist.',
                        $row[$map['id']]
                    ),
                    'row' => $rowNum
                );
            }

            $bookingInput = array(
                'facility' => $row[$map['booking_facility']],
                'start' => $row[$map['booking_start']],
                'end' => $row[$map['booking_end']],
            );

            $rates = array();
            for ($i = 0; $i < 3; $i++) {
                $mapOffset = $i + 1;
                if (!isset($map["booking_rate{$mapOffset}_id"]) || !isset($row[$map["booking_rate{$mapOffset}_id"]]))
                    continue;

                $rate = array(
                    'rate' => $row[$map["booking_rate{$mapOffset}_id"]]
                );

                if (isset($map["booking_rate{$mapOffset}_start"])) {
                    $rate['start'] = $row[$map["booking_rate{$mapOffset}_start"]];
                }

                if (isset($map["booking_rate{$mapOffset}_end"])) {
                    $rate['end'] = $row[$map["booking_rate{$mapOffset}_end"]];
                }

                $bookingInput['rates'][] = $rate;
            }

            if (isset($map['booking_billing_effective'])) {
                $bookingInput['billing_effective'] = $row[$map['booking_billing_effective']];
            }

            if (isset($map['booking_billing_through'])) {
                $bookingInput['billing_through'] = $row[$map['booking_billing_through']];
            }

            $mealplanInput = array();

            if (isset($map['mealplan_plan'])) {
                $mealplanInput['plan'] = $row[$map['mealplan_plan']];
            }

            if (isset($map['mealplan_start'])) {
                $mealplanInput['start'] = $row[$map['mealplan_start']];
            }

            if (isset($map['mealplan_end'])) {
                $mealplanInput['end'] = $row[$map['mealplan_end']];
            }

            for ($i = 0; $i < 1; $i++) {
                $mapOffset = $i + 1;
                if (!isset($map["mealplan_rate{$mapOffset}_id"]) || !isset($row[$map["mealplan_rate{$mapOffset}_id"]]))
                    continue;

                $rate = array(
                    'rate' => $row[$map["mealplan_rate{$mapOffset}_id"]]
                );

                if (isset($map["mealplan_rate{$mapOffset}_start"])) {
                    $rate['start'] = $row[$map["mealplan_rate{$mapOffset}_start"]];
                }

                if (isset($map["mealplan_rate{$mapOffset}_end"])) {
                    $rate['end'] = $row[$map["mealplan_rate{$mapOffset}_end"]];
                }

                $mealplanInput['rates'][] = $rate;
            }

            if (isset($map['mealplan_billing_effective'])) {
                $mealplanInput['billing_effective'] = $row[$map['mealplan_billing_effective']];
            }

            if (isset($map['mealplan_billing_through'])) {
                $mealplanInput['billing_through'] = $row[$map['mealplan_billing_through']];
            }

            // Set up our form
            $bookingForm = new \Tillikum_Form();
            $bookingForm->setElementsBelongTo('booking');

            $facilitySubForm = new \Tillikum\Form\Booking\Facility();
            $facilitySubForm->setElementsBelongTo('facility');
            $facilitySubForm->setPerson($person);
            $bookingForm->addSubForm($facilitySubForm, 'facility');

            $mealplanSubForm = new \Tillikum\Form\Booking\Mealplan();
            $mealplanSubForm->setElementsBelongTo('mealplan');
            $mealplanSubForm->setPerson($person);
            $mealplanSubForm->plan->setRequired(false);
            $mealplanSubForm->start->setRequired(false);
            $mealplanSubForm->end->setRequired(false);
            $bookingForm->addSubForm($mealplanSubForm, 'mealplan');

            if (array_key_exists($bookingInput['facility'], $rangesByFacility)) {
                foreach ($rangesByFacility[$bookingInput['facility']] as $range) {
                    $facilitySubForm->addExtraDateRange($range);
                }
            }

            $massInput = array(
                'facility' => $bookingInput,
                'mealplan' => $mealplanInput
            );

            if (!$bookingForm->isValid($massInput)) {
                $it = new RecursiveArrayIterator($bookingForm->getMessages());
                $itit = new RecursiveIteratorIterator($it);
                foreach ($itit as $message) {
                    return array(
                        'result' => false,
                        'reason' => $message,
                        'row' => $rowNum
                    );
                }
            }

            $values = $bookingForm->getValues(true);

            $facilityBooking = $values['facility']['booking'];
            $facilityBooking->created_by = $identity;
            $facilityBooking->updated_by = $identity;

            $ffnHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('FullFacilityName');
            $view = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->view;
            $facilityNameArray = $ffnHelper->fullFacilityName($facilityBooking->facility, null, $facilityBooking->start);

            if ($facilityBooking->billing) {
                $bookingCharges = $facilityBooking->processCharges(
                    $view->fullFacilityName($facilityNameArray)
                );

                foreach ($bookingCharges as $charge) {
                    $charge->created_by = $identity;
                    $this->data[$person->id]['charges'][] = $charge;
                }
            }

            $this->data[$person->id]['bookings'][] = $facilityBooking;

            $mealplanBooking = $values['mealplan']['booking'];
            if (null !== $mealplanBooking) {
                $mealplanBooking->created_by = $identity;
                $mealplanBooking->updated_by = $identity;

                if ($mealplanBooking->billing) {
                    $mealplanCharges = $mealplanBooking->processCharges(
                        $mealplanBooking->plan
                    );

                    foreach ($mealplanCharges as $charge) {
                        $charge->created_by = $identity;
                        $this->data[$person->id]['charges'][] = $charge;
                    }
                }

                $this->data[$person->id]['mealplans'][] = $mealplanBooking;
            }

            $rangesByFacility[$bookingInput['facility']][] = new \Vo\DateRange(
                $facilityBooking->start,
                $facilityBooking->end
            );
        }

        return array('result' => true);
    }
}
