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

class DataTableInvoice extends AbstractHelper
{
    public function dataTableInvoice($invoices)
    {
        $ac = $this->_actionController;

        $actions = array(
            'details' => $ac->getAcl()->isAllowed('_user', 'billing_invoice', 'read'),
        );

        $rows = array();
        foreach ($invoices as $invoice) {
            $rows[] = array(
                'actions' => $actions,
                'created_at' => $invoice->created_at,
                'created_by' => $invoice->created_by,
                'description' => $invoice->description,
                'details_uri' => $ac->getHelper('Url')->direct(
                    'view',
                    'invoice',
                    'billing',
                    array(
                        'id' => $invoice->id,
                    )
                ),
                'id' => $invoice->id,
            );
        }

        return $rows;
    }

    public function direct($invoices)
    {
        return $this->dataTableInvoice($invoices);
    }
}
