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

class DataTableMealplanSummary extends AbstractHelper
{
    public function dataTableMealplanSummary($bookings)
    {
        $ac = $this->_actionController;
        $view = $ac->view;

        if (count($bookings) === 0) {
            return array();
        }

        $actions = array(
            'delete' => $ac->getAcl()->isAllowed('_user', 'mealplan_booking', 'write'),
            'details' => $ac->getAcl()->isAllowed('_user', 'mealplan_booking', 'read'),
            'edit' => $ac->getAcl()->isAllowed('_user', 'mealplan_booking', 'write')
        );

        $rows = array();
        foreach ($bookings as $booking) {
            $bookingDateRange = new DateRange($booking->start, $booking->end);

            $rows[] = array(
                'actions' => $actions,
                'end' => $booking->end,
                'id' => $booking->id,
                'is_current' => $bookingDateRange->includes(new DateTime(date('Y-m-d'))),
                'name' => $view->escape($booking->mealplan->name),
                'start' => $booking->start,
                'delete_uri' => $view->url(array(
                    'module' => 'mealplan',
                    'controller' => 'delete',
                    'action' => 'index',
                    'id' => $booking->id
                ), null, true),
                'details_uri' => $view->url(array(
                    'module' => 'mealplan',
                    'controller' => 'index',
                    'action' => 'view',
                    'id' => $booking->id
                ), null, true),
                'edit_uri' => $view->url(array(
                    'module' => 'mealplan',
                    'controller' => 'edit',
                    'action' => 'index',
                    'id' => $booking->id
                ), null, true)
            );
        }

        return $rows;
    }

    public function direct($bookings)
    {
        return $this->dataTableMealplanSummary($bookings);
    }
}
