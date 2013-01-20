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

class DataTableRateConfirm extends AbstractHelper
{
    public function dataTableRateConfirm($rates)
    {
        $ac = $this->_actionController;
        $view = $ac->view;

        $rows = array();
        foreach ($rates as $rate) {
            $rows[] = array(
                'end' => $rate->end,
                'start' => $rate->start,
                'rule_description' => $rate->rule->description,
            );
        }

        return $rows;
    }

    public function direct($rates)
    {
        return $this->dataTableRateConfirm($rates);
    }
}
