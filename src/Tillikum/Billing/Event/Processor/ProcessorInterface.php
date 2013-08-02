<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Billing\Event\Processor;

use Tillikum\Entity\Billing\Event\Event;

interface ProcessorInterface
{
    /**
     * Process a billing event.
     *
     * @param $event Event to process.
     */
    public function process(Event $event);
}
