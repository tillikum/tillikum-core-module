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

class Weekly implements StrategyInterface
{
    public function getDescription()
    {
        return 'Bill based on the number of weeks between two dates, prorated daily.';
    }

    public function getName()
    {
        return 'Weekly';
    }

    public function process(Event $event, RuleConfig $config)
    {
        $entries = new ArrayCollection;

        $eventRange = new DateRange(
            $event->start,
            $event->end
        );

        // Days: Day difference + 1
        $totalDays = (int) $eventRange->getStart()
            ->diff($eventRange->getEnd())
            ->format('%R%a') + 1;

        // The total number of full weeks
        $weeks = (int) floor($totalDays / 7);
        // The number of leftover days after full weeks are counted
        $leftoverDays = (int) $totalDays % 7;

        $amountPerWeek = new Money(
            $config->amount,
            $config->currency
        );

        // amount per week * number of weeks
        $weekTotal = $amountPerWeek->mul($weeks);
        // (amount per week / 7) * leftover days (prorate)
        $leftoverDayTotal = $amountPerWeek
            ->div(7)
            ->mul($leftoverDays);

        // If we have solid weeks, make an entry for that total
        if ($weeks > 0) {
            $fullWeekEndDate = clone $eventRange->getEnd();
            $fullWeekEndDate->modify("-{$leftoverDays} day");

            $entry = new entry;
            $entry->amount = $weekTotal->round(2);
            $entry->currency = $weekTotal->getCurrency();
            $entry->code = $config->code;
            $entry->description = sprintf(
                '%s to %s (%s %s) @ %s per week',
                $eventRange->getStart()->format('Y-m-d'),
                $fullWeekEndDate->format('Y-m-d'),
                $weeks,
                $weeks == 1 ? 'week' : 'weeks',
                $amountPerWeek->format()
            );

            $entries->add($entry);
        }

        if ($leftoverDays > 0) {
            // Start 1 day after the end of the previous full week(s), if there
            // were any
            $partialWeekStartDate = clone $eventRange->getEnd();
            $partialWeekStartDate->modify("-{$leftoverDays} day")
                ->modify('+1 day');

            $entry = new Entry();
            $entry->amount = $leftoverDayTotal->round(2);
            $entry->currency = $leftoverDayTotal->getCurrency();
            $entry->code = $config->code;
            $entry->description = sprintf(
                '%s to %s (%s %s) @ %s per week (prorated daily)',
                $partialWeekStartDate->format('Y-m-d'),
                $eventRange->getEnd()->format('Y-m-d'),
                $leftoverDays,
                $leftoverDays == 1 ? 'day' : 'days',
                $amountPerWeek->format()
            );

            $entries->add($entry);
        }

        return $entries;
    }
}
