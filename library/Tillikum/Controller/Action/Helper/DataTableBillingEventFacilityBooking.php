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

class DataTableBillingEventFacilityBooking extends AbstractHelper
{
    public function dataTableBillingEventFacilityBooking($events)
    {
        $actionController = $this->_actionController;

        $rows = array();
        foreach ($events as $event) {
            $rows[] = array(
                'created_at' => $event->created_at,
                'created_by' => $event->created_by,
                'end' => $event->end,
                'facility_html' => sprintf(
                    '<a href="%s">%s</a>',
                    $actionController->getHelper('Url')->direct(
                        'view',
                        'facility',
                        'facility',
                        array(
                            'id' => $event->facility->id,
                        )
                    ),
                    $actionController->view->escape(
                        implode(' ', $event->facility->getNamesOnDate($event->created_at))
                    )
                ),
                'id' => $event->id,
                'is_credit' => $event->is_credit,
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
                'start' => $event->start,
            );
        }

        return $rows;
    }

    public function direct($events)
    {
        return $this->dataTableBillingEventFacilityBooking($events);
    }
}
