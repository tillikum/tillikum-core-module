<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\View\Helper;

use Zend_View_Helper_FormText as FormTextHelper;

/**
 * Helper to generate a "datetime" element
 *
 * XXX: This helper will likely be replaced by a ZF helper (or a way to specify
 * the input type). You should switch to the recommended method when/if it gets
 * introduced.
 */
class FormDatetime extends FormTextHelper
{
    /**
     * Generates a 'datetime' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are used in place of added parameters.
     *
     * @param  mixed  $value   The element value.
     * @param  array  $attribs Attributes for the element tag.
     * @return string The element XHTML.
     */
    public function formDatetime($name, $value = null, $attribs = null)
    {
        return str_replace(
            '<input type="text"',
            '<input type="datetime"',
            $this->formText($name, $value, $attribs)
        );
    }
}
