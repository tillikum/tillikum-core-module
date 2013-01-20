<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Action\Helper;

class DataTableBillingEventConfirm extends \Zend_Controller_Action_Helper_Abstract
{
    public function dataTableBillingEventConfirm($events)
    {
        $ac = $this->_actionController;

        if (count($events) === 0) {
            return array();
        }

        $rows = array();
        foreach ($events as $event) {
            $rows[] = array(
                'rule' => $event->rule->description,
                'is_credit' => $event->is_credit,
                'start' => $event->start,
                'end' => $event->end,
            );
        }

        return $rows;
    }

    public function direct($charges)
    {
        return $this->dataTableBillingEventConfirm($charges);
    }
}
