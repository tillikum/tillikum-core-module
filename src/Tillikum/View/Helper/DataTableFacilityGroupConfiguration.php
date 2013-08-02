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
 * Helper for rendering a facility group configuration data table
 */
class DataTableFacilityGroupConfiguration extends AbstractHelper
{
    public function dataTableFacilityGroupConfiguration($rows)
    {
        // XXX: how to determine here which renderer to use?
        return $this->buildingConfiguration($rows);
    }

    protected function buildingConfiguration($rows)
    {
        return $this->view->partial(
            '_partials/datatable/facilitygroup_building_configuration.phtml',
            array(
                'rows' => $rows
            )
        );
    }
}
