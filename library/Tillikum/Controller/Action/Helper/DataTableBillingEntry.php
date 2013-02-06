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

class DataTableBillingEntry extends AbstractHelper
{
    public function dataTableBillingEntry($entries)
    {
        $ac = $this->_actionController;

        $rows = array();
        foreach ($entries as $entry) {
            $rows[] = array(
                'amount' => $entry->amount,
                'code' => $entry->code,
                'created_at' => $entry->created_at,
                'created_by' => $entry->created_by,
                'currency' => $entry->currency,
                'description' => $entry->description,
                'id' => $entry->id,
                'invoice' => $entry->invoice ? $entry->invoice->description : '',
            );
        }

        return $rows;
    }

    public function direct($entries)
    {
        return $this->dataTableBillingEntry($entries);
    }
}
