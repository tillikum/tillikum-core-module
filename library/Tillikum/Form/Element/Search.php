<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

/**
 * Tillikum search input element
 *
 * XXX: This element will likely be replaced by a ZF element (or a way to
 * specify the input type). You should switch to the recommended method when/if
 * it gets introduced.
 */
class Tillikum_Form_Element_Search extends Zend_Form_Element_Xhtml
{
    public $helper = 'formSearch';

    public function __construct($spec, $options = null)
    {
        $localOptions = array(
            'filters' => array(
                'StringTrim'
            )
        );

        if (isset($options)) {
            $options = array_replace_recursive(
                $localOptions,
                $options
            );
        } else {
            $options = $localOptions;
        }

        parent::__construct($spec, $options);
    }
}
