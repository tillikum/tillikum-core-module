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

class DataTableBookingBillingRate extends AbstractHelper
{
    public function dataTableBookingBillingRate($rates)
    {
        $ac = $this->_actionController;
        $view = $ac->view;

        $rows = array();
        foreach ($rates as $rate) {
            $rows[] = array(
                'id' => $rate->id,
                'rule' => $rate->rule->description,
                'start' => $rate->start,
                'end' => $rate->end,
                'created_at' => $rate->created_at,
                'created_by' => $rate->created_by,
                'updated_at' => $rate->updated_at,
                'updated_by' => $rate->updated_by,
            );
        }

        return $rows;
    }

    public function direct($rates)
    {
        return $this->dataTableBookingBillingRate($rates);
    }
}
