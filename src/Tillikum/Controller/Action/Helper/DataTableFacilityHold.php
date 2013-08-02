<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Action\Helper;

use DateTime;
use Vo\DateRange;
use Zend_Controller_Action_Helper_Abstract as AbstractHelper;

class DataTableFacilityHold extends AbstractHelper
{
    public function dataTableFacilityHold($facility)
    {
        $ac = $this->_actionController;
        $view = $ac->view;

        if (count($facility->holds) === 0) {
            return array();
        }

        $actions = array(
            'delete' => $ac->getAcl()->isAllowed('_user', 'facility', 'write'),
            'edit' => $ac->getAcl()->isAllowed('_user', 'facility', 'write')
        );

        $rows = array();
        foreach ($facility->holds as $hold) {
            $holdDateRange = new DateRange($hold->start, $hold->end);

            $row = array(
                'actions' => $actions,
                'start' => $hold->start ?: new DateTime('1000-01-01'),
                'end' => $hold->end ?: new DateTime('9999-01-01'),
                'description' => isset($hold->description) ? $view->escape($hold->description) : '',
                'gender' => $hold->gender ?: 'U',
                'id' => $hold->id,
                'is_current' => $holdDateRange->includes(new DateTime(date('Y-m-d'))),
                'space' => $hold->space ?: 0,
                'delete_uri' => $view->url(array(
                    'module' => 'facility',
                    'controller' => 'hold',
                    'action' => 'delete',
                    'id' => $hold->id
                ), null, true),
                'edit_uri' => $view->url(array(
                    'module' => 'facility',
                    'controller' => 'hold',
                    'action' => 'edit',
                    'id' => $hold->id
                ), null, true)
            );

            $rows[] = $row;
        }

        return $rows;
    }

    public function direct($facility)
    {
        return $this->dataTableFacilityHold($facility);
    }
}
