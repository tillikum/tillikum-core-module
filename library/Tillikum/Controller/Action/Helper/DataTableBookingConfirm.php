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

class DataTableBookingConfirm extends AbstractHelper
{
    public function dataTableBookingConfirm($bookings)
    {
        $ac = $this->_actionController;
        $view = $ac->view;

        $rows = array();
        foreach ($bookings as $booking) {
            $facility = $booking->facility;

            $rows[] = array(
                'checkin_at' => $booking->checkin_at,
                'checkin_by' => $booking->checkin_by,
                'checkout_at' => $booking->checkout_at,
                'checkout_by' => $booking->checkout_by,
                'end' => $booking->end,
                'name' => implode(' ', $facility->getNamesOnDate($booking->start)),
                'start' => $booking->start,
                'note' => $booking->note,
                'uri' => $view->url(array(
                    'module' => 'facility',
                    'controller' => 'facility',
                    'action' => 'view',
                    'id' => $facility->id
                ), null, true)
            );
        }

        return $rows;
    }

    public function direct($bookings)
    {
        return $this->dataTableBookingConfirm($bookings);
    }
}
