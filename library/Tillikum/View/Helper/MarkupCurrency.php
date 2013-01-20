<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\View\Helper;

use Zend_View_Helper_Abstract as AbstractHelper;

/**
 * Helper for handling currency markup
 */
class MarkupCurrency extends AbstractHelper
{
    /**
     * Returns HTML markup for a given currency value
     *
     * @param  float  $amount
     * @return string
     */
    public function markupCurrency($amount, $currency = 'USD')
    {
        return sprintf(
            '<span data-currency="%s" data-amount="%s">%s</span>',
            $currency,
            $amount,
            $this->view->escape($this->view->formatCurrency($amount, $currency))
        );
    }
}
