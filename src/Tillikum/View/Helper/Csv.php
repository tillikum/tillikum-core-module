<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\View\Helper;

use Zend_Layout as Layout;
use Zend_View_Helper_Abstract as AbstractHelper;

/**
 * View helper for encoding data as a CSV
 */
class Csv extends AbstractHelper
{
    /**
     * Encode data as CSV, disable layouts, and set response header
     *
     * The $filename parameter will have ".csv" appended to it. If $header is
     * true, an appropriate header will be sent indicating that the CSV
     * contains headers. If $keepLayouts is true, layouts are not disabled.
     *
     * @param  mixed       $data
     * @param  string      $filename
     * @param  bool        $header
     * @param  bool        $keepLayouts
     * @return string|void
     */
    public function csv($data, $keepLayouts = false, $encodeData = true)
    {
        if ($encodeData) {
            $o = fopen('php://temp/maxmemory:' . 1 * 1024 * 1024, 'r+');
            foreach ($data as $datum) {
                fputcsv($o, $datum);
            }
            rewind($o);
            $output = stream_get_contents($o);
            fclose($o);
        } else {
            $output = $data;
        }

        if (!$keepLayouts) {
            $layout = Layout::getMvcInstance();
            if ($layout instanceof Layout) {
                $layout->disableLayout();
            }
        }

        return $output;
    }
}
