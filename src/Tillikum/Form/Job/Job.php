<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Job;

class Job extends \Tillikum_Form
{
    public $entity;

    public function bind($entity)
    {
        $this->entity = $entity;

        if ($entity->is_dry_run !== null) {
            $this->is_dry_run->setValue($entity->is_dry_run);
        }

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->entity)) {
            return;
        }

        $this->entity->is_dry_run = $this->is_dry_run->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $identity = new \Zend_Form_Element_Hidden(
            'identity',
            array(
                'decorators' => array(
                    'ViewHelper',
                ),
            )
        );

        $isDryRun = new \Zend_Form_Element_Checkbox(
            'is_dry_run',
            array(
                'attribs' => array(
                    'checked' => 'checked',
                ),
                'description' => 'The job will run normally, but avoid making'
                               . ' permanent changes to your data.',
                'label' => 'Is this a "dry run"?',
            )
        );

        $this->setMethod('POST')
            ->addElements(
                array(
                    $identity,
                    $isDryRun,
                    $this->createSubmitElement(
                        array(
                            'label' => 'Run job',
                            'order' => PHP_INT_MAX,
                        )
                    )
                )
            );
    }
}
