<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Person;

class Tag extends \Tillikum_Form
{
    protected $tag;

    public function bind($tag)
    {
        $this->tag = $tag;

        $this->id->setValue($tag->id);
        $this->name->setValue($tag->name);
        $this->is_active->setValue($tag->is_active);
        $this->warning->setValue($tag->warning);

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->tag)) {
            return;
        }

        $this->tag->id = $this->id->getValue();
        $this->tag->name = $this->name->getValue();
        $this->tag->is_active = (bool) $this->is_active->getValue();
        $this->tag->warning = $this->warning->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $id = new \Zend_Form_Element_Text(
            'id',
            array(
                'label' => 'Tag ID',
                'required' => true
            )
        );

        $name = new \Zend_Form_Element_Text(
            'name',
            array(
                'label' => 'Tag name',
                'required' => true
            )
        );

        $isActive = new \Zend_Form_Element_Checkbox(
            'is_active',
            array(
                'description' => 'Deactivating tags will hide them from menus'
                               . ' around the application, but do not affect'
                               . ' users who are currently tagged.',
                'label' => 'Is this tag active?',
                'value' => true
            )
        );

        $warning = new \Zend_Form_Element_Text(
            'warning',
            array(
                'description' => 'The text entered here, if any, will appear'
                               . ' when a person is tagged with this tag.',
                'label' => 'Warning text'
            )
        );

        $this->addElements(array(
            $id,
            $name,
            $isActive,
            $warning,
            $this->createSubmitElement(array(
                'label' => 'Save'
            ))
        ));
    }
}
