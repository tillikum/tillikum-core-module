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

class DataTableFacilityBooking extends AbstractHelper
{
    public function dataTableFacilityBooking($facility)
    {
        $ac = $this->_actionController;
        $view = $ac->view;

        if (count($facility->bookings) === 0) {
            return array();
        }

        $actions = array(
            'delete' => $ac->getAcl()->isAllowed('_user', 'facility_booking', 'write'),
            'details' => $ac->getAcl()->isAllowed('_user', 'facility_booking', 'read'),
            'edit' => $ac->getAcl()->isAllowed('_user', 'facility_booking', 'write')
        );

        $rows = array();
        foreach ($facility->bookings as $booking) {
            $bookingDateRange = new DateRange($booking->start, $booking->end);

            $row = array(
                'actions' => $actions,
                'id' => $booking->id,
                'is_current' => $bookingDateRange->includes(new DateTime(date('Y-m-d'))),
                'person_uri' => $ac->getHelper('Url')->direct(
                    'view', 'person', 'person', array('id' => $booking->person->id)
                ),
                'person_name' => $view->escape($booking->person->display_name),
                'person_gender' => $booking->person->gender,
                'start' => $booking->start ?: new DateTime('1900-01-01'),
                'end' => $booking->end ?: new DateTime('2099-01-01'),
                'delete_uri' => $view->url(array(
                    'module' => 'booking',
                    'controller' => 'delete',
                    'action' => 'index',
                    'id' => $booking->id
                ), null, true),
                'details_uri' => $view->url(array(
                    'module' => 'booking',
                    'controller' => 'index',
                    'action' => 'view',
                    'id' => $booking->id
                ), null, true),
                'edit_uri' => $view->url(array(
                    'module' => 'booking',
                    'controller' => 'edit',
                    'action' => 'index',
                    'id' => $booking->id
                ), null, true)
            );

            $rows[] = $row;
        }

        return $rows;
    }

    public function direct($facility)
    {
        return $this->dataTableFacilityBooking($facility);
    }
}
