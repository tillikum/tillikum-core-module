<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\View\Helper;

use DateTime;
use IntlDateFormatter;
use Locale;
use Zend_Controller_Front as FrontController;
use Zend_View_Helper_Abstract as AbstractHelper;

/**
 * Format a date + time
 */
class FormatDateTime extends AbstractHelper
{
    /**
     * Can we pass a DateTime object to IntlDateFormatter#format?
     *
     * @todo PHP 5.3.4: Remove
     * @var bool
     */
    protected $canFormatDateTime;

    /**
     * @var IntlDateFormatter
     */
    protected $dateFormatter;

    public function __construct($locale = null)
    {
        $this->canFormatDateTime = version_compare(PHP_VERSION, '5.3.4') >= 0;

        if ($locale === null) {
            $locale = Locale::getDefault();
        }

        $this->dateFormatter = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::SHORT,
            IntlDateFormatter::MEDIUM,
            date_default_timezone_get()
        );
    }

    /**
     * Format input date
     *
     * This method is designed for formatting a date + time, not just a date.
     *
     * This method uses the IntlDateFormatter, and accepts either a DateTime
     * object or a parseable (by strtotime) date string.
     *
     * @param  DateTime|string $input
     * @return string
     */
    public function formatDateTime($input)
    {
        if ($input instanceof DateTime) {
            if ($this->canFormatDateTime) {
                return $this->dateFormatter->format($input);
            } else {
                $value = (int) $input->format('U');
            }
        } else {
            $value = strtotime($input);
        }

        return $this->dateFormatter->format($value);
    }
}
