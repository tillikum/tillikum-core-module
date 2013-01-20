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

class DataTableBillingConfirm extends AbstractHelper
{
    public function dataTableBillingConfirm($billing)
    {
        if (null === $billing) {
            return array();
        }

        $rows = array();
        $booking = $billing->booking;

        $rows[] = array(
            'through' => $billing->through
        );

        return $rows;
    }

    public function direct($billing)
    {
        return $this->dataTableBillingConfirm($billing);
    }
}
