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
use Zend_View_Helper_Abstract as AbstractHelper;

/**
 * Helper for handling date (not date + time) <time> markup
 */
class MarkupDate extends AbstractHelper
{
    protected $formatter;

    public function __construct($locale = null)
    {
        $this->formatter = new FormatDate($locale);
    }

    /**
     * Returns HTML5 <time> markup for a given date
     *
     * This method is designed for formatting dates, not a date + time. It
     * uses the formatDate view helper for the human-readable part.
     *
     * @param  DateTime $input
     * @return string
     */
    public function markupDate(DateTime $input = null)
    {
        if ($input === null) {
            return '';
        }

        return sprintf(
            '<time datetime="%s">%s</time>',
            $input->format('Y-m-d'),
            $this->view->escape($this->formatter->formatDate($input))
        );
    }
}
