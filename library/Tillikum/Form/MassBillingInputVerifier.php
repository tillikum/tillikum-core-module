<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

class Tillikum_Form_MassBillingInputVerifier extends Tillikum_Form_MassInputVerifier
{
    protected $charges;

    public function getCharges()
    {
        return $this->charges;
    }

    public function checkMappedData($map, $rows)
    {
        $personGateway = new \TillikumX\Model\PersonGateway();

        // Set up the form that we will use to validate charges
        $chargeForm = new Tillikum_Form_Charge();

        $identity = \Zend_Auth::getInstance()->hasIdentity()
            ? Zend_Auth::getInstance()->getIdentity()
            : '_system';

        $rowNum = 1;
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

            $options = $chargeForm->charge->getMultiOptions();

            if (!isset($options[$row[$map['code']]])) {
                return array(
                    'result' => false,
                    'reason' => sprintf(
                        'The code "%s" does not exist.',
                        $row[$map['code']]
                    ),
                    'row' => $rowNum
                );
            }

            $chargeValues = array(
                'charge' => $row[$map['code']],
                'amount' => $row[$map['amount']],
                'description' => $options[$row[$map['code']]],
                'note' => !empty($map['note']) ? $row[$map['note']] : ''
            );

            if (!$chargeForm->isValid($chargeValues)) {
                $elements = $chargeForm->getMessages();
                foreach ($elements as $messages) {
                    foreach ($messages as $message) {
                        return array(
                            'result' => false,
                            'reason' => $message,
                            'row' => $rowNum
                        );
                    }
                }

                return array(
                    'result' => false,
                    'reason' => 'Failed validation; reason unknown.',
                    'row' => $rowNum
                );
            }

            $values = $chargeForm->getValues(true);

            $chargeTemplateGateway = new \Tillikum\Model\ChargeTemplateGateway();
            $chargeGateway = new \Tillikum\Model\ChargeGateway();
            $charge = new \Tillikum\Model\Charge();

            $chargeTemplate = $chargeTemplateGateway->fetch($values['charge']);

            $charge->id = $chargeGateway->generateId();
            $charge->amount = $values['amount'];
            // XXX: OSU
            $charge->description = preg_replace('/ \(.*\)$/', '', $values['description']);
            // XXX: OSU
            $charge->detail_code = $chargeTemplate->detail_code;
            $charge->created_by = $identity;

            if (isset($chargeTemplate->export_to)) {
                $charge->export = new stdClass();
                $charge->export->to = $chargeTemplate->export_to;
            }

            if (!empty($values['note'])) {
                $charge->note = $values['note'];
            }

            $this->charges[$person->id][] = $charge;

            $chargeForm->reset();
        }

        return array(
            'result' => true
        );
    }
}
