<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

class Tillikum_Form_FacilityAdmin extends Tillikum_Form
{
    public function init()
    {
        parent::init();

        $name = new Zend_Form_Element_Text(
            'name',
            array(
                'label' => 'Name'
            )
        );

        $capacity = new Zend_Form_Element_Text(
            'capacity',
            array(
                'label' => 'Capacity',
                'validators' => array(
                    new Zend_Validate_Int()
                )
            )
        );

        $type = new Zend_Form_Element_Select(
            'ftype',
            array(
                'label' => 'Facility Type',
                'required' => true,
                'multiOptions' => array(
                    'building' => 'Building',
                    'community' => 'Community',
                    'floor' => 'Floor',
                    'homestay' => 'Homestay Family',
                    'room' => 'Room',
                    'suite' => 'Suite',
                    'wing' => 'Wing'
                )
            )
        );

        $submit = new Tillikum_Form_Element_Submit('submit');

        $this->addElements(
            array(
                $name,
                $capacity,
                $type,
                $submit
            )
        );
    }
}
