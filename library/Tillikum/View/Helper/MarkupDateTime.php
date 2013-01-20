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
 * Helper for handling date + time <time> markup
 */
class MarkupDateTime extends AbstractHelper
{
    protected $formatter;

    public function __construct($locale = null)
    {
        $this->formatter = new FormatDateTime($locale);
    }

    /**
     * Returns HTML5 <time> markup for a given date + time
     *
     * @param  DateTime $input
     * @return string
     */
    public function markupDateTime(DateTime $input = null)
    {
        if ($input === null) {
            return '';
        }

        return sprintf(
            '<time datetime="%s">%s</time>',
            gmdate('c', $input->format('U')),
            $this->view->escape($this->formatter->formatDateTime($input))
        );
    }
}
