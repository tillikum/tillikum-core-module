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

class DataTableBillingRuleMealplanBookingConfig extends AbstractHelper
{
    public function dataTableBillingRuleMealplanBookingConfig($configs)
    {
        $actionController = $this->_actionController;

        $actions = array(
            'copy' => $actionController->getAcl()->isAllowed('_user', 'billing_rule', 'write'),
            'delete' => $actionController->getAcl()->isAllowed('_user', 'billing_rule', 'write'),
            'edit' => $actionController->getAcl()->isAllowed('_user', 'billing_rule', 'write'),
            'view' => $actionController->getAcl()->isAllowed('_user', 'billing_rule', 'read'),
        );

        $rows = array();
        foreach ($configs as $config) {
            $strategy = $actionController->getServiceManager()
                ->get($config->strategy);

            $rows[] = array(
                'actions' => $actions,
                'amount' => $config->amount,
                'code' => $config->code,
                'created_at' => $config->created_at,
                'created_by' => $config->created_by,
                'currency' => $config->currency,
                'description' => $config->description,
                'end' => $config->end,
                'id' => $config->id,
                'rule' => $config->rule,
                'start' => $config->start,
                'strategy_description' => $strategy->getDescription(),
                'strategy_name' => $strategy->getName(),
                'updated_at' => $config->updated_at,
                'updated_by' => $config->updated_by,
                'copy_uri' => $actionController->getHelper('Url')->direct(
                    'copy',
                    'ruleconfig',
                    'billing',
                    array(
                        'id' => $config->id,
                    )
                ),
                'delete_uri' => $actionController->getHelper('Url')->direct(
                    'delete',
                    'ruleconfig',
                    'billing',
                    array(
                        'id' => $config->id,
                    )
                ),
                'edit_uri' => $actionController->getHelper('Url')->direct(
                    'edit',
                    'ruleconfig',
                    'billing',
                    array(
                        'id' => $config->id,
                    )
                ),
                'view_uri' => $actionController->getHelper('Url')->direct(
                    'view',
                    'ruleconfig',
                    'billing',
                    array(
                        'id' => $config->id,
                    )
                ),
            );
        }

        return $rows;
    }

    public function direct($configs)
    {
        return $this->dataTableBillingRuleMealplanBookingConfig($configs);
    }
}
