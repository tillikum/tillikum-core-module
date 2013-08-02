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

class DataTableMealplanConfirm extends AbstractHelper
{
    public function dataTableMealplanConfirm($bookings)
    {
        $rows = array();
        foreach ($bookings as $booking) {
            $mealplan = $booking->mealplan;

            $rows[] = array(
                'end' => $booking->end,
                'name' => $mealplan->name,
                'start' => $booking->start,
                'note' => $booking->note,
            );
        }

        return $rows;
    }

    public function direct($bookings)
    {
        return $this->dataTableMealplanConfirm($bookings);
    }
}
