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

class DataTableJobHistory extends AbstractHelper
{
    public function dataTableJobHistory($jobs)
    {
        $actionController = $this->getActionController();
        $serviceManager = $actionController->getServiceManager();

        if (count($jobs) === 0) {
            return array();
        }

        $actions = array(
            'view' => $actionController->getAcl()->isAllowed('_user', 'job', 'read'),
        );

        $urlHelper = $actionController->getHelper('Url');

        $rows = array();
        foreach ($jobs as $job) {
            $rows[] = array(
                'actions' => $actions,
                'updated_at' => $job->updated_at,
                'updated_by' => $job->updated_by,
                'created_at' => $job->created_at,
                'created_by' => $job->created_by,
                'id' => $job->id,
                'is_dry_run' => $job->is_dry_run,
                'name' => $serviceManager->get($job->class_name)->getName(),
                'status' => $job->status,
                'view_uri' => $urlHelper->url(
                    array(
                        'module' => 'job',
                        'controller' => 'job',
                        'action' => 'view',
                        'id' => $job->id,
                    ),
                    null,
                    true
                ),
            );
        }

        return $rows;
    }

    public function direct($jobs)
    {
        return $this->dataTableJobHistory($jobs);
    }
}
