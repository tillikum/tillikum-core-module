<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Action\Helper;

use Zend_Controller_Action_Helper_Abstract as AbstractHelper;

class DataTableReport extends AbstractHelper
{
    public function dataTableReport($rows)
    {
        $header = array();
        $body = array();
        if (count($rows) > 0) {
            $header = array_shift($rows);
            $body = $rows;
        }

        return array(
            'header' => $header,
            'body' => $body,
        );
    }

    public function direct($rows)
    {
        return $this->dataTableReport($rows);
    }
}
