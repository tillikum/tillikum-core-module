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

class DataTableMealplanBilling extends AbstractHelper
{
    public function dataTableMealplanBilling($billing)
    {
        if ($billing === null) {
            return array();
        }

        $rows = array();

        $rows[] = array(
            'id' => $billing->id,
            'through' => $billing->through,
            'created_at' => $billing->created_at,
            'created_by' => $billing->created_by,
            'updated_at' => $billing->updated_at,
            'updated_by' => $billing->updated_by,
        );

        return $rows;
    }

    public function direct($billing)
    {
        return $this->dataTableMealplanBilling($billing);
    }
}
