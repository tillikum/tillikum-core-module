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

class DataTableBookingDetail extends AbstractHelper
{
    public function dataTableBookingDetail($bookings)
    {
        $ac = $this->_actionController;

        $actions = array(
            'delete' => $ac->getAcl()->isAllowed('_user', 'facility_booking', 'write'),
            'details' => $ac->getAcl()->isAllowed('_user', 'facility_booking', 'read'),
            'edit' => $ac->getAcl()->isAllowed('_user', 'facility_booking', 'write')
        );

        $rows = array();
        foreach ($bookings as $booking) {
            $facility = $booking->facility;

            $bookingDateRange = new DateRange($booking->start, $booking->end);

            $rows[] = array(
                'actions' => $actions,
                'checkin_at' => $booking->checkin_at,
                'checkin_by' => $booking->checkin_by,
                'checkout_at' => $booking->checkout_at,
                'checkout_by' => $booking->checkout_by,
                'created_at' => $booking->created_at,
                'created_by' => $booking->created_by,
                'updated_at' => $booking->updated_at,
                'updated_by' => $booking->updated_by,
                'note' => $booking->note,
                'end' => $booking->end,
                'id' => $booking->id,
                'is_current' => $bookingDateRange->includes(new DateTime(date('Y-m-d'))),
                'name' => implode(' ', $facility->getNamesOnDate($booking->start)),
                'start' => $booking->start,
                'uri' => $ac->getHelper('Url')->direct(
                    'view',
                    'facility',
                    'facility',
                    array(
                        'id' => $facility->id,
                    )
                ),
                'delete_uri' => $ac->getHelper('Url')->direct(
                    'index',
                    'delete',
                    'booking',
                    array(
                        'id' => $booking->id,
                    )
                ),
                'details_uri' => $ac->getHelper('Url')->direct(
                    'view',
                    'index',
                    'booking',
                    array(
                        'id' => $booking->id,
                    )
                ),
                'edit_uri' => $ac->getHelper('Url')->direct(
                    'index',
                    'edit',
                    'booking',
                    array(
                        'id' => $booking->id,
                    )
                ),
            );
        }

        return $rows;
    }

    public function direct($bookings)
    {
        return $this->dataTableBookingDetail($bookings);
    }
}
