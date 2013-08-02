<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

/**
 * Form decorator for displaying warnings for a given form
 */
class Tillikum_Form_Decorator_Warnings extends Zend_Form_Decorator_Abstract
{
    /**
     * Render the warnings in an HTML div
     *
     * If the element has an associated view, the content will be escaped.
     * Otherwise, the warning will not be escaped.
     *
     * Currently outputs the following:
     *
     * <pre>
     * <div role="alert" data-is-warning="true">{content}</div>
     * </pre>
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view = $element->getView();

        $warningContent = '';
        foreach ($element->getWarnings() as $warning) {
            $warningContent .= '<div role="alert" data-is-warning="true">';
            if ($view !== null) {
                $warningContent .= $view->escape($warning);
            } else {
                $warningContent .= $warning;
            }
            $warningContent .= '</div>';
        }

        switch ($this->getPlacement()) {
            case self::APPEND:
                return $content . $warningContent;
            case self::PREPEND:
                return $warningContent . $content;
            default:
                return $warningContent;
        }
    }
}
