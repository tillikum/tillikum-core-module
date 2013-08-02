<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Person;

class Search extends \Tillikum_Form
{
    public function init()
    {
        parent::init();

        $search = new \Tillikum_Form_Element_Search(
            'search',
            array(
                'attribs' => array(
                    'placeholder' => 'Person’s name or identifier'
                ),
                'label' => 'Search'
            )
        );

        $this->setMethod('GET')
        ->addElements(array(
            $search,
            $this->createSubmitElement(array('label' => 'Search'))
        ));
    }
}
