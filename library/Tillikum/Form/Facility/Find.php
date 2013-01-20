<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Facility;

class Find extends \Tillikum_Form
{
    public function init()
    {
        parent::init();

        $name = new \Tillikum_Form_Element_Search(
            'name',
            array(
                'attribs' => array(
                    'placeholder' => 'Type the name of a facility',
                ),
                'description' => 'A facility is anything that can be booked,' .
                                 ' such as a room.',
                'label' => 'Facility name',
            )
        );

        $this->addElements(
            array(
                $name
            )
        );
    }
}
