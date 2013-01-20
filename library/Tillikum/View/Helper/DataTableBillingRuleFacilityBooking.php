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

class DataTableBillingRuleFacilityBooking extends AbstractHelper
{
    public function dataTableBillingRuleFacilityBooking($rows)
    {
        return $this->view->partial(
            '_partials/datatable/billing_rule_facility_booking.phtml',
            'billing',
            array(
                'rows' => $rows
            )
        );
    }
}
