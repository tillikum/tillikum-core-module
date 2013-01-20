<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Facility;

class Type extends \Tillikum_Form
{
    protected static $typeOptions = array(
        '' => '',
        'Tillikum\Entity\Facility\Room\Room' => 'Room'
    );

    public function init()
    {
        parent::init();

        $type = new \Zend_Form_Element_Select(
            'type',
            array(
                'label' => 'Which type of facility do you want to add?',
                'multiOptions' => self::$typeOptions,
                'required' => true
            )
        );

        $this->addElements(array(
            $type,
            $this->createSubmitElement(array('label' => 'Next...'))
        ));
    }
}
