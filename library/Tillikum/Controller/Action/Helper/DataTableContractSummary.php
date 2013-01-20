<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Action\Helper;

use DateTime;
use Vo\DateRange;
use Zend_Controller_Action_Helper_Abstract as AbstractHelper;

class DataTableContractSummary extends AbstractHelper
{
    public function dataTableContractSummary($signatures)
    {
        $ac = $this->_actionController;
        $view = $ac->view;

        if (count($signatures) === 0) {
            return array();
        }

        $actions = array(
            'edit' => $ac->getAcl()->isAllowed('_user', 'contract', 'write')
        );

        $rows = array();
        foreach ($signatures as $signature) {
            $contract = $signature->contract;

            $contractDateRange = new DateRange($contract->start, $contract->end);

            $rows[] = array(
                'actions' => $actions,
                'contract' => $contract->name,
                'id' => $signature->id,
                'is_cancelled' => $signature->is_cancelled,
                'is_cosigned' => $signature->is_cosigned,
                'is_current' => $contractDateRange->includes(new DateTime(date('Y-m-d'))),
                'is_signed' => $signature->is_signed,
                'requires_cosigned' => $signature->requires_cosigned,
                'signed_at' => $signature->signed_at,
                'edit_uri' => $view->url(array(
                    'module' => 'contract',
                    'controller' => 'signature',
                    'action' => 'edit',
                    'id' => $signature->id
                ), null, true)
            );
        }

        return $rows;
    }

    public function direct($signatures)
    {
        return $this->dataTableContractSummary($signatures);
    }
}
