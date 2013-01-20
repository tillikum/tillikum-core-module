<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\View\Helper;

use Vo\Money;
use Zend_View_Helper_Abstract as AbstractHelper;

/**
 * Format a currency amount according to the specified locale.
 */
class FormatCurrency extends AbstractHelper
{
    /**
     * Format a currency amount according to the specified locale.
     *
     * @param $value float Value to format
     * @return string
     */
    public function formatCurrency($amount, $currency = 'USD')
    {
        $money = new Money($amount, $currency);

        return $money->format();
    }
}
