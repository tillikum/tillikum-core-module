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

class DataTablePendingBookingSummary extends AbstractHelper
{
    public function dataTablePendingBookingSummary($pendingBookings)
    {
        $actionController = $this->getActionController();

        $actions = array(
            'view' => $actionController->getAcl()->isAllowed('_user', 'facility_booking', 'read'),
        );

        $rows = array();
        foreach ($pendingBookings as $serviceName => $pendingBooking) {
            $rows[] = array(
                'actions' => $actions,
                'name' => $pendingBooking->getName(),
                'description' => $pendingBooking->getDescription(),
                'serviceName' => $serviceName,
                'view_uri' => $actionController->getHelper('Url')->direct(
                    'view',
                    'pending',
                    'booking',
                    array(
                        'name' => $serviceName,
                    )
                ),
            );
        }

        return $rows;
    }

    public function direct($pendingBookings)
    {
        return $this->dataTablePendingBookingSummary($pendingBookings);
    }
}
