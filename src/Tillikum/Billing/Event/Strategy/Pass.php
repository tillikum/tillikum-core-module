<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Billing\Event\Strategy;

use Doctrine\Common\Collections\ArrayCollection;
use Tillikum\Entity\Billing\Event\Event;
use Tillikum\Entity\Billing\Entry\Entry;
use Tillikum\Entity\Billing\Rule\Config\Config as RuleConfig;

class Pass implements StrategyInterface
{
    public function getDescription()
    {
        return 'Pass-through billing for ad-hoc events that require no modification.';
    }

    public function getName()
    {
        return 'Pass-through';
    }

    public function process(Event $event, RuleConfig $config)
    {
        $entries = new ArrayCollection();

        $entry = new Entry();
        $entry->amount = $event->amount;
        $entry->currency = $event->currency;
        $entry->code = $config->code;
        $entry->description = $event->description;

        $entries->add($entry);

        return $entries;
    }
}
