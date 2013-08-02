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

class DataTableJobSummary extends AbstractHelper
{
    public function dataTableJobSummary($jobs)
    {
        $actionController = $this->getActionController();

        if (count($jobs) === 0) {
            return array();
        }

        $actions = array(
            'create' => $actionController->getAcl()->isAllowed('_user', 'job', 'write'),
        );

        $urlHelper = $actionController->getHelper('Url');

        $rows = array();
        foreach ($jobs as $serviceName => $job) {
            $rows[] = array(
                'name' => $job->getName(),
                'description' => $job->getDescription(),
                'serviceName' => $serviceName,
            );
        }

        return $rows;
    }

    public function direct($jobs)
    {
        return $this->dataTableJobSummary($jobs);
    }
}
