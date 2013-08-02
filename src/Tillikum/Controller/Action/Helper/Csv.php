<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Action\Helper;

use Zend_Controller_Action_HelperBroker as HelperBroker;
use Zend_Controller_Action_Helper_Abstract as AbstractHelper;
use Tillikum\View\Helper\Csv as CsvViewHelper;

/**
 * Action helper for encoding data as a CSV
 */
class Csv extends AbstractHelper
{
    /**
     * Suppress exit when sendCsv() called
     *
     * @var boolean
     */
    public $suppressExit = false;

    /**
     * Create CSV response
     *
     * Encodes and returns data as CSV. Content-Type header set to
     * 'text/csv', and disables layouts and viewRenderer (if being
     * used).
     *
     * @param array   $data
     * @param boolean $keepLayouts
     * @param boolean $encodeData  Provided data is already CSV
     *
     * @return string
     */
    public function encodeCsv($data, $keepLayouts = false, $encodeData = true)
    {
        $csvHelper = new CsvViewHelper;
        $data = $csvHelper->csv($data, $keepLayouts, $encodeData);

        if (!$keepLayouts) {
            HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
        }

        return $data;
    }

    /**
     * Encode CSV response and immediately send
     *
     * @param mixed   $data
     * @param boolean $keepLayouts
     * @param  $encodeData Encode $data as CSV?
     *
     * @return string|void
     */
    public function sendCsv($data, $filename = 'output', $header = true, $keepLayouts = false, $encodeData = true)
    {
        $data = $this->encodeCsv($data, $keepLayouts, $encodeData);

        $header = $header ? 'present' : 'absent';
        $filename = basename($filename, '.csv');

        $response = $this->getResponse();
        $response->setHeader('Cache-Control', 'private', true);
        $response->setHeader('Pragma', 'private', true);
        $response->setHeader('Content-Type', "text/csv;charset=UTF-8;header=$header", true);
        $response->setHeader('Content-Disposition', "attachment;filename=$filename.csv", true);
        $response->setBody($data);

        if (!$this->suppressExit) {
            $response->sendResponse();
            exit;
        }

        return $data;
    }

    /**
     * Strategy pattern: call helper as helper broker method
     *
     * Allows encoding CSV. If $sendNow is true, immediately sends CSV
     * response.
     *
     * @param  mixed       $data
     * @param  boolean     $sendNow
     * @param  boolean     $keepLayouts
     * @param  boolean     $encodeData  Encode $data as CSV?
     * @return string|void
     */
    public function direct($data, $filename = 'output', $header = true, $sendNow = true, $keepLayouts = false, $encodeData = true)
    {
        if ($sendNow) {
            return $this->sendCsv($data, $filename, $header, $keepLayouts, $encodeData);
        }

        return $this->encodeCsv($data, $keepLayouts, $encodeData);
    }
}
