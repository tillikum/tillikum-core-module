<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

class Tillikum_Form_MassBilling extends Tillikum_Form
{
    public function init()
    {
        parent::init();

        $input = new Zend_Form_Element_Textarea(
            'textinput',
            array(
                'label' => 'Tab-separated input',
                'description' => 'This input is generated automatically if you perform a copy-paste operation from most spreadsheet software.',
                'required' => true
            )
        );

        $is_include_header = new Zend_Form_Element_Checkbox(
            'is_include_header',
            array(
                'label' => 'Does the spreadsheet include column headers?'
            )
        );

        $submit = new Tillikum_Form_Element_Submit('submit');
        $submit->setLabel('Next…');

        $this->setMethod('POST')
        ->addElements(
            array(
                $input,
                $is_include_header,
                $submit
            )
        );
    }
}
