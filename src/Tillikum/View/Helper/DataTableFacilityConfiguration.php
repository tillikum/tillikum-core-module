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
 * Helper for rendering a facility configuration data table
 */
class DataTableFacilityConfiguration extends AbstractHelper
{
    public function dataTableFacilityConfiguration($rows)
    {
        // XXX: how to determine here which renderer to use?
        return $this->roomConfiguration($rows);
    }

    protected function roomConfiguration($rows)
    {
        return $this->view->partial(
            '_partials/datatable/facility_room_configuration.phtml',
            array(
                'rows' => $rows
            )
        );
    }
}
