<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Billing;

class Rule extends \Tillikum_Form
{
    public $entity;

    public function bind($entity)
    {
        $this->entity = $entity;

        $this->description->setValue($entity->description);

        return $this;
    }

    public function bindValues()
    {
        if (!$this->entity) {
            return;
        }

        $this->entity->description = $this->description->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $description = new \Zend_Form_Element_Text(
            'description',
            array(
                'label' => 'Description',
                'required' => true
            )
        );

        $this->setMethod('POST')
            ->addElements(
                array(
                    $description,
                    $this->createSubmitElement(
                        array(
                            'label' => 'Save',
                            'order' => PHP_INT_MAX,
                        )
                    )
                )
            );
    }
}
