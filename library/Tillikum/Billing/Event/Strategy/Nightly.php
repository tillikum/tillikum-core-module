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
use Tillikum\Entity\Billing\Entry\Entry;
use Tillikum\Entity\Billing\Event\Event;
use Tillikum\Entity\Billing\Rule\Config\Config as RuleConfig;
use Vo\Money;

class Nightly implements StrategyInterface
{
    public function getDescription()
    {
        return 'Bill based on the number of nights between two dates.';
    }

    public function getName()
    {
        return 'Nightly';
    }

    public function process(Event $event, RuleConfig $config)
    {
        $entries = new ArrayCollection;

        // Nights: Day difference
        $nights = (int) $event->start
                ->diff($event->end)
                ->format('%R%a');

        // Nothing to do.
        if ($nights <= 0) {
            return $entries;
        }

        $amountPerNight = new Money(
            $config->amount,
            $config->currency
        );

        // Safely calculate nightly amount
        // amountPerNight * number of nights
        $total = $amountPerNight->mul($nights);

        $entry = new Entry();
        $entry->amount = $total->round(2);
        $entry->currency = $total->getCurrency();
        $entry->code = $config->code;
        $entry->description = sprintf(
            '%s to %s (%s %s) @ %s per night',
            $event->start->format('Y-m-d'),
            $event->end->format('Y-m-d'),
            $nights,
            $nights === 1 ? 'night' : 'nights',
            $amountPerNight->format()
        );

        $entries->add($entry);

        return $entries;
    }
}
