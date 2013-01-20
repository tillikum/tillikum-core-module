<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Job;

class BillingEventProcessor extends Job
{
    public function bind($entity)
    {
        parent::bind($entity);

        $this->invoice_description->setValue($entity->invoice_description);

        return $this;
    }

    public function bindValues()
    {
        parent::bindValues();

        if (!isset($this->entity)) {
            return;
        }

        $this->entity->invoice_description = $this->invoice_description->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $invoiceDescription = new \Zend_Form_Element_Text(
            'invoice_description',
            array(
                'description' => 'Examples: Fall term invoicing, ' .
                                 date('n/j/Y') . ' invoicing',
                'label' => 'Invoice description',
                'required' => true,
            )
        );

        $this->addElements(
            array(
                $invoiceDescription,
            )
        );
    }
}
