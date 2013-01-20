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
 * Helper for rendering the booking summary data table
 */
class DataTableBookingSummary extends AbstractHelper
{
    public function dataTableBookingSummary($rows)
    {
        return $this->view->partial(
            '_partials/datatable/booking_summary.phtml',
            array(
                'rows' => $rows
            )
        );
    }
}
