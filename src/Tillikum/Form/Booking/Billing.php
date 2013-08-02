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

class Billing extends \Tillikum_Form
{
    public $billing;

    public function bind($billing)
    {
        $this->billing = $billing;

        $this->id->setValue($billing->id);
        $this->through->setValue($billing->through ? $billing->through->format('Y-m-d') : '');

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->billing)) {
            return;
        }

        $this->billing->through = $this->through->getValue() ? new DateTime($this->through->getValue()) : null;

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

        $through = new \Tillikum_Form_Element_Date(
            'through',
            array(
                'label' => 'Billed through',
            )
        );

        $this->addElements(
            array(
                $id,
                $through,
                $this->createSubmitElement(array('label' => 'Next...'))
            )
        );
    }
}
