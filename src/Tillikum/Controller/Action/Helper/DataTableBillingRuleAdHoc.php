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

class DataTableBillingRuleAdHoc extends AbstractHelper
{
    public function dataTableBillingRuleAdHoc($rules)
    {
        $actionController = $this->_actionController;

        $actions = array(
            'copy' => $actionController->getAcl()->isAllowed('_user', 'billing_rule', 'write'),
            'edit' => $actionController->getAcl()->isAllowed('_user', 'billing_rule', 'write'),
            'view' => $actionController->getAcl()->isAllowed('_user', 'billing_rule', 'read'),
        );

        $rows = array();
        foreach ($rules as $rule) {
            $rows[] = array(
                'actions' => $actions,
                'created_at' => $rule->created_at,
                'created_by' => $rule->created_by,
                'description' => $rule->description,
                'id' => $rule->id,
                'updated_at' => $rule->updated_at,
                'updated_by' => $rule->updated_by,
                'edit_uri' => $actionController->getHelper('Url')->direct(
                    'edit',
                    'rule',
                    'billing',
                    array(
                        'id' => $rule->id,
                    )
                ),
                'view_uri' => $actionController->getHelper('Url')->direct(
                    'view',
                    'rule',
                    'billing',
                    array(
                        'id' => $rule->id,
                    )
                ),
            );
        }

        return $rows;
    }

    public function direct($rules)
    {
        return $this->dataTableBillingRuleAdHoc($rules);
    }
}
