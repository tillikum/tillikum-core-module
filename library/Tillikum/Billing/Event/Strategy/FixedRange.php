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
use Vo\DateRange;
use Vo\Money;

class FixedRange implements StrategyInterface
{
    public function getDescription()
    {
        return 'Bill a fixed amount between two days, prorated daily.';
    }

    public function getName()
    {
        return 'Fixed amount for a date range';
    }

    public function process(Event $event, RuleConfig $config)
    {
        $entries = new ArrayCollection;

        $configRange = new DateRange(
            $config->start,
            $config->end
        );

        $eventRange = new DateRange(
            $event->start,
            $event->end
        );

        if ($configRange->isEmpty()) {
            return $entries;
        }

        $amountPerRange = new Money(
            $config->amount,
            $config->currency
        );

        $configDays = (int) $configRange->getStart()
                    ->diff($configRange->getEnd())
                    ->format('%R%a') + 1;

        $totalDays = (int) $eventRange->getStart()
                   ->diff($eventRange->getEnd())
                   ->format('%R%a') + 1;

        // If we match up nicely, just bill at the fixed value and we're done
        if ($eventRange->equals($configRange)) {
            $rangeTotal = $amountPerRange;

            $entry = new Entry();
            $entry->amount = $rangeTotal->round(2);
            $entry->currency = $rangeTotal->getCurrency();
            $entry->code = $config->code;
            $entry->description = sprintf(
                '%s to %s (%s %s) @ %s for the entire range',
                $event->start->format('Y-m-d'),
                $event->end->format('Y-m-d'),
                $totalDays,
                $totalDays == 1 ? 'day' : 'days',
                $amountPerRange->format()
            );

            $entries->add($entry);
        } else {
            // (amount per fixed range / days in fixed range) * days billed
            $proratedTotal = $amountPerRange
                           ->div($configDays)
                           ->mul($totalDays);

            $entry = new Entry();
            $entry->amount = $proratedTotal->round(2);
            $entry->currency = $proratedTotal->getCurrency();
            $entry->code = $config->code;
            $entry->description = sprintf(
                '%s to %s (%s %s) @ %s for part of %s to %s (%s %s) (prorated daily)',
                $event->start->format('Y-m-d'),
                $event->end->format('Y-m-d'),
                $totalDays,
                $totalDays == 1 ? 'day' : 'days',
                $amountPerRange->format(),
                $configRange->getStart()->format('Y-m-d'),
                $configRange->getEnd()->format('Y-m-d'),
                $configDays,
                $configDays == 1 ? 'day' : 'days'
            );

            $entries->add($entry);
        }

        return $entries;
    }
}
