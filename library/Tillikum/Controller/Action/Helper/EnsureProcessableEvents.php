<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Action\Helper;

use Zend\Di\Di;
use Zend_Controller_Action_Helper_Abstract as AbstractHelper;
use Tillikum\Billing\Event\Processor;

class EnsureProcessableEvents extends AbstractHelper
{
    /**
     * Ensures a set of events will be processable
     *
     * @throws \Zend_Controller_Exception when events are not processable
     */
    public function ensureProcessableEvents(Di $di, $events)
    {
        $bookingProcessor = new Processor\BookingProcessor($di);

        try {
            foreach ($events as $event) {
                $bookingProcessor->process($event);
            }
        } catch (Processor\Exception\RuntimeException $e) {
            throw new \Zend_Controller_Exception($e->getMessage(), 400, $e);
        }
    }

    public function direct(Di $di, $events)
    {
        return $this->ensureProcessableEvents($di, $events);
    }
}
