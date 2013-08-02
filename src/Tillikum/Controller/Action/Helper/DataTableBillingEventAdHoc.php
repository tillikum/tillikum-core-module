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

class DataTableBillingEventAdHoc extends AbstractHelper
{
    public function dataTableBillingEventAdHoc($events)
    {
        $actionController = $this->_actionController;

        $rows = array();
        foreach ($events as $event) {
            $rows[] = array(
                'amount' => $event->amount,
                'created_at' => $event->created_at,
                'created_by' => $event->created_by,
                'currency' => $event->currency,
                'description' => $event->description,
                'effective' => $event->effective,
                'id' => $event->id,
                'is_processed' => $event->is_processed,
                'person_html' => sprintf(
                    '<a href="%s">%s</a>',
                    $actionController->getHelper('Url')->direct(
                        'view',
                        'person',
                        'person',
                        array(
                            'id' => $event->person->id,
                        )
                    ),
                    $actionController->view->escape($event->person->display_name)
                ),
                'rule' => $event->rule->description,
            );
        }

        return $rows;
    }

    public function direct($events)
    {
        return $this->dataTableBillingEventAdHoc($events);
    }
}
