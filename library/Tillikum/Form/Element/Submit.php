<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

/**
 * Tillikum form submission element
 */
class Tillikum_Form_Element_Submit extends \Zend_Form_Element_Submit
{
    public function __construct($spec, $options = null)
    {
        $localOptions = array(
            'decorators' => array(
                'ViewHelper',
                array(
                    'HtmlTag',
                    array(
                        'tag' => 'dd'
                    )
                )
            ),
            'ignore' => true,
            'label' => 'Submit'
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
