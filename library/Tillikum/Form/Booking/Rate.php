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

class Rate extends \Tillikum_Form
{
    public $rate;

    public function bind($rate)
    {
        $this->rate = $rate;

        $this->id->setValue($rate->id);
        $this->rule_id->setValue($rate->rule ? $rate->rule->id : '');
        $this->start->setValue($rate->start ? $rate->start->format('Y-m-d') : '');
        $this->end->setValue($rate->end ? $rate->end->format('Y-m-d') : '');

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->rate)) {
            return;
        }

        $this->rate->rule = $this->em->find(
            'Tillikum\Entity\Billing\Rule\Rule',
            $this->rule_id->getValue()
        );
        $this->rate->start = new DateTime($this->start->getValue());
        $this->rate->end = new DateTime($this->end->getValue());

        return $this;
    }

    public function init()
    {
        parent::init();

        $checkedId = 'id' . uniqid();

        $id = new \Zend_Form_Element_Hidden(
            'id',
            array(
                'decorators' => array(
                    'ViewHelper',
                ),
                'required' => false,
            )
        );

        $deleteMe = new \Zend_Form_Element_Checkbox(
            'delete_me',
            array(
                'attribs' => array(
                    'ng-model' => $checkedId,
                ),
                'description' => 'The removal will occur once you submit this form.',
                'label' => 'Remove this rate?',
            )
        );

        $ruleId = new \Zend_Form_Element_Select(
            'rule_id',
            array(
                'attribs' => array(
                    'ng-readonly' => $checkedId,
                ),
                'label' => 'Rule',
                'multiOptions' => array('' => ''),
                'required' => true,
            )
        );

        $start = new \Tillikum_Form_Element_Date(
            'start',
            array(
                'attribs' => array(
                    'ng-readonly' => $checkedId,
                ),
                'label' => 'Start date',
                'required' => true,
            )
        );

        $end = new \Tillikum_Form_Element_Date(
            'end',
            array(
                'attribs' => array(
                    'ng-readonly' => $checkedId,
                ),
                'label' => 'End date',
                'required' => true,
            )
        );

        $this->addElements(
            array(
                $id,
                $deleteMe,
                $ruleId,
                $start,
                $end,
                $this->createSubmitElement(array('label' => 'Save'))
            )
        );
    }

    public function isValid($data)
    {
        $dissolvedData = $data;

        if ($this->isArray()) {
            $dissolvedData = $this->_dissolveArrayValue(
                $data,
                $this->getElementsBelongTo()
            );
        }

        $skipDetailedValidation = true;
        foreach (array('rule_id', 'start', 'end') as $member) {
            if (!empty($dissolvedData[$member])) {
                $skipDetailedValidation = false;
                $this->rule_id->setRequired(true);
                $this->start->setRequired(true);
                $this->end->setRequired(true);
            }
        }

        if (!parent::isValid($data)) {
            return false;
        }

        if ($skipDetailedValidation) {
            return true;
        }

        $data = $dissolvedData;

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

        return true;
    }
}
