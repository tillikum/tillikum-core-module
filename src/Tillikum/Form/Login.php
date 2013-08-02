<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form;

class Login extends \Tillikum_Form
{
    public function init()
    {
        parent::init();

        $username = new \Zend_Form_Element_Text(
            'username',
            array(
                'label' => 'User name',
                'required' => true,
            )
        );

        $password = new \Zend_Form_Element_Password(
            'password',
            array(
                'label' => 'Password',
                'required' => true,
            )
        );

        $this->addElements(
            array(
                $username,
                $password,
                $this->createSubmitElement(array('label' => 'Log in')),
            )
        );
    }
}
