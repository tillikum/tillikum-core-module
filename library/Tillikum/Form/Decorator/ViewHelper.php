<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

class Tillikum_Form_Decorator_ViewHelper extends Zend_Form_Decorator_ViewHelper
{
    /**
     * Retrieve element attributes
     *
     * Set id to element name and/or array item.
     *
     * @return array
     */
    public function getElementAttribs()
    {
        $attribs = parent::getElementAttribs();

        $element = $this->getElement();

        if ($element->isRequired()) {
            $element->setAttrib('required', 'required');
            $element->setAttrib('aria-required', 'true');
            $attribs['required'] = 'required';
            $attribs['aria-required'] = 'true';
        }

        if ($element->hasErrors()) {
            $element->setAttrib('aria-invalid', 'true');
            $attribs['aria-invalid'] = 'true';
        }

        return $attribs;
    }
}
