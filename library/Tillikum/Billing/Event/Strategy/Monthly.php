<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Billing\Event\Strategy;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Tillikum\Entity\Billing\Entry\Entry;
use Tillikum\Entity\Billing\Event\Event;
use Tillikum\Entity\Billing\Rule\Config\Config as RuleConfig;
use Vo\Money;

class Monthly implements StrategyInterface
{
    public function getDescription()
    {
        return 'Bill based on the number of months between two dates, prorated daily.';
    }

    public function getName()
    {
        return 'Monthly';
    }

    public function process(Event $event, RuleConfig $config)
    {
        $entries = new ArrayCollection;

        // Create a pointer because we’re about to get procedural
        $pointer = clone($event->start);

        // If we don't start on the first of the month, we will need to
        // calculate "leading" days before the first full month (if there is
        // one).
        $leadingDays = 0;
        $leadingDaysInMonth = 0;
        if ($pointer->format('j') != 1) {
            $leadingStartAudit = $pointer->format('Y-m-d');

            $leadingEnd = min(
                $event->end,
                new DateTime($pointer->format('Y-m-t'))
            );

            $leadingEndAudit = $leadingEnd->format('Y-m-d');

            $leadingDaysInMonth = (int) $pointer->format('t');

            $leadingDays = (int) $pointer
                ->diff($leadingEnd)
                ->format('%R%a') + 1;

            $pointer->modify("+{$leadingDays} days");
        }

        // Initial number of full months
        $months = 0;
        // Get the date of the last day in the month our pointer is in
        $endOfMonth = new DateTime($pointer->format('Y-m-t'));
        $fullMonthStartAudit = $pointer->format('F Y');
        // If the pointer points at the first, and the end date is at or at or
        // beyond the end of the current month, we can count a full month.
        while ($pointer->format('j') == 1 && $event->end >= $endOfMonth) {
            $fullMonthEndAudit = $endOfMonth->format('F Y');
            $months += 1;

            // Now that we counted a full month, advance pointer 1 day past the
            // end of the month, which should be the first day of the next month
            $pointer = clone $endOfMonth;
            $pointer->modify('+1 day');

            // Update the new end of the month based on the new pointer value
            $endOfMonth = new DateTime($pointer->format('Y-m-t'));
        }

        $trailingDays = 0;
        $trailingDaysInMonth = 0;
        if ($pointer <= $event->end) {
            $trailingStartAudit = $pointer->format('Y-m-d');
            $trailingEndAudit = $event->end->format('Y-m-d');
            $trailingDaysInMonth = (int) $pointer->format('t');

            // Our pointer should be exactly where it needs to be to calculate any
            // "trailing" days
            $trailingDays = (int) $pointer
                ->diff($event->end)
                ->format('%R%a') + 1;
        }

        $amountPerMonth = new Money(
            $config->amount,
            $config->currency
        );

        // amount per month * number of months
        $monthTotal = $amountPerMonth->mul($months);

        if ($leadingDays > 0) {
            // (amount per month / number of days in month) * leftover days (prorate)
            $leadingDayTotal = $amountPerMonth
                ->div($leadingDaysInMonth)
                ->mul($leadingDays);

            $entry = new Entry();
            $entry->amount = $leadingDayTotal->round(2);
            $entry->currency = $leadingDayTotal->getCurrency();
            $entry->code = $config->code;
            $entry->description = sprintf(
                '%s to %s (%s %s) @ %s per month (prorated daily)',
                $leadingStartAudit,
                $leadingEndAudit,
                $leadingDays,
                $leadingDays == 1 ? 'day' : 'days',
                $amountPerMonth->format()
            );

            $entries->add($entry);
        }

        // If we have solid months, make an entry for that total
        if ($months > 0) {
            $entry = new Entry();
            $entry->amount = $monthTotal->round(2);
            $entry->currency = $monthTotal->getCurrency();
            $entry->code = $config->code;
            $entry->description = sprintf(
                '%s (%s %s) @ %s per month',
                $months == 1 ? $fullMonthStartAudit : "$fullMonthStartAudit to $fullMonthEndAudit",
                $months,
                $months == 1 ? 'month' : 'months',
                $amountPerMonth->format()
            );

            $entries->add($entry);
        }

        if ($trailingDays > 0) {
            // (amount per month / number of days in month) * leftover days (prorate)
            $trailingDayTotal = $amountPerMonth
                ->div($trailingDaysInMonth)
                ->mul($trailingDays);

            $entry = new Entry();
            $entry->amount = $trailingDayTotal->round(2);
            $entry->currency = $trailingDayTotal->getCurrency();
            $entry->code = $config->code;
            $entry->description = sprintf(
                '%s to %s (%s %s) @ %s per month (prorated daily)',
                $trailingStartAudit,
                $trailingEndAudit,
                $trailingDays,
                $trailingDays == 1 ? 'day' : 'days',
                $amountPerMonth->format()
            );

            $entries->add($entry);
        }

        return $entries;
    }
}
