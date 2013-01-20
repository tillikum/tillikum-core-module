<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\FacilityGroup;

class Find extends \Tillikum_Form
{
    public function init()
    {
        parent::init();

        $name = new \Tillikum_Form_Element_Search(
            'name',
            array(
                'attribs' => array(
                    'placeholder' => 'Type the name of a facility group',
                ),
                'description' => 'A facility group is a container for facilities,' .
                                 ' such as a building.',
                'label' => 'Find a facility group',
            )
        );

        $this->addElements(
            array(
                $name
            )
        );
    }
}
