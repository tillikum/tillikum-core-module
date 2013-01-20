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

class DataTableReportSummary extends AbstractHelper
{
    public function dataTableReportSummary($reports)
    {
        $rows = array();
        foreach ($reports as $serviceName => $report) {
            $rows[] = array(
                'name' => $report->getName(),
                'description' => $report->getDescription(),
                'serviceName' => $serviceName,
            );
        }

        return $rows;
    }

    public function direct($reports)
    {
        return $this->dataTableReportSummary($reports);
    }
}
