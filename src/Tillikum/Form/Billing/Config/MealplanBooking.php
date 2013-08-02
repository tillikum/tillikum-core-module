<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Billing\Config;

class MealplanBooking extends Config
{
    public $entity;

    public function bind($entity)
    {
        parent::bind($entity);

        $this->currency->setValue($entity->currency ?: 'USD');
        $this->amount->setValue($entity->amount);

        return $this;
    }

    public function bindValues()
    {
        parent::bindValues();

        if (!isset($this->entity)) {
            return;
        }

        $this->entity->currency = $this->currency->getValue();
        $this->entity->amount = $this->amount->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $currency = new \Zend_Form_Element_Hidden(
            'currency',
            array(
                'decorators' => array(
                    'ViewHelper',
                ),
                'required' => true,
                'value' => 'USD',
            )
        );

        $amount = new \Tillikum_Form_Element_Number(
            'amount',
            array(
                'attribs' => array(
                    'min' => '-9999.99',
                    'max' => '9999.99',
                    'step' => '0.01',
                    'title' => 'Value must be precise to no more than 2' .
                               ' decimal places'
                ),
                'label' => 'Amount',
                'required' => true,
                'validators' => array(
                    'Float',
                    new \Zend_Validate_Between(-9999.99, 9999.99)
                )
            )
        );

        $this->addElements(
            array(
                $currency,
                $amount,
            )
        );
    }
}
