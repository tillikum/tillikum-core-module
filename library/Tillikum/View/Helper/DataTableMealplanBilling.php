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
 * Helper for rendering the meal plan billing data table
 */
class DataTableMealplanBilling extends AbstractHelper
{
    public function dataTableMealplanBilling($rows)
    {
        return $this->view->partial(
            '_partials/datatable/mealplan_billing.phtml',
            array(
                'rows' => $rows
            )
        );
    }
}
