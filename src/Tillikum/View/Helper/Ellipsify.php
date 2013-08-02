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
 * Shorten a string to a specified length and add an ellipsis to the end
 */
class Ellipsify extends AbstractHelper
{
    public function ellipsify($string, $length, $slop = 4)
    {
        if (mb_strlen($string) > $length) {
            $string = mb_substr($string, 0, $length - 1);

            $last_space = mb_strrpos($string, ' ');

            if ($last_space > $length - $slop) {
                $string = mb_substr($string, 0, $last_space);
            }

            $string .= '…';
        }

        return $string;
    }
}
