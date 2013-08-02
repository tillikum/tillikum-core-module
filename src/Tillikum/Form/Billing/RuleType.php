<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Billing;

class RuleType extends \Tillikum_Form
{
    protected static $typeOptions = array(
        '' => '',
        'Tillikum\Entity\Billing\Rule\AdHoc' => 'Ad hoc',
        'Tillikum\Entity\Billing\Rule\FacilityBooking' => 'Facility booking',
        'Tillikum\Entity\Billing\Rule\MealplanBooking' => 'Meal plan booking',
    );

    public function init()
    {
        parent::init();

        $type = new \Zend_Form_Element_Select(
            'type',
            array(
                'label' => 'Which type of billing rule do you want to add?',
                'multiOptions' => self::$typeOptions,
                'required' => true,
            )
        );

        $this->addElements(
            array(
                $type,
                $this->createSubmitElement(array('label' => 'Next...'))
            )
        );
    }
}
