<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\View\Helper;

use Zend_View_Helper_Abstract as AbstractHelper;

/**
 * Helper for rendering the booking confirmation data table
 */
class DataTableBookingConfirm extends AbstractHelper
{
    public function dataTableBookingConfirm($rows)
    {
        return $this->view->partial(
            '_partials/datatable/booking_confirm.phtml',
            array(
                'rows' => $rows
            )
        );
    }
}
