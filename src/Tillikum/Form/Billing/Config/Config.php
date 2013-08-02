<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Billing\Config;

use DateTime;
use Doctrine\ORM\EntityManager;

class Config extends \Tillikum_Form
{
    public $entity;

    public function bind($config)
    {
        $this->entity = $config;

        $this->code->setValue($config->code);
        $this->description->setValue($config->description);
        $this->end->setValue($config->end ? $config->end->format('Y-m-d') : '');
        $this->start->setValue($config->start ? $config->start->format('Y-m-d') : '');
        $this->strategy->setValue($config->strategy);

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->entity)) {
            return;
        }

        $this->entity->code = $this->code->getValue();
        $this->entity->description = $this->description->getValue();
        $this->entity->end = new DateTime($this->end->getValue());
        $this->entity->start = new DateTime($this->start->getValue());
        $this->entity->strategy = $this->strategy->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $strategy = new \Zend_Form_Element_Select(
            'strategy',
            array(
                'label' => 'Strategy',
                'multiOptions' => array(),
                'required' => true,
            )
        );

        $code = new \Zend_Form_Element_Text(
            'code',
            array(
                'required' => true,
                'label' => 'Code',
                'filters' => array(
                    'StringTrim',
                )
            )
        );

        $description = new \Zend_Form_Element_Text(
            'description',
            array(
                'label' => 'Description',
                'filters' => array(
                    'StringTrim',
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

        $this->addElements(
            array(
                $strategy,
                $code,
                $description,
                $start,
                $end,
            )
        );
    }
}
