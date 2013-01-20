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
 * Helper for rendering the rate confirm table
 */
class DataTableRateConfirm extends AbstractHelper
{
    public function dataTableRateConfirm($rows)
    {
        return $this->view->partial(
            '_partials/datatable/rate_confirm.phtml',
            array(
                'rows' => $rows
            )
        );
    }
}
