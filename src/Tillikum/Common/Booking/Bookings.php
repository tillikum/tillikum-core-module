<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Common\Booking;

use DateTime;
use Vo\DateRange;

final class Bookings
{
    public static function createCurrentFilter()
    {
        $currentDate = new DateTime(date('Y-m-d'));

        return self::createIncludedDateFilter($currentDate);
    }

    public static function createIncludedDateFilter(DateTime $date)
    {
        return function ($booking) use ($date) {
            $bookingRange = new DateRange(
                $booking->start, $booking->end
            );

            return $bookingRange->includes($date);
        };
    }

    public static function createIncludedDateRangeFilter(DateRange $range)
    {
        return function ($booking) use ($range) {
            $bookingRange = new DateRange(
                $booking->start, $booking->end
            );

            return $range->includes($bookingRange);
        };
    }

    public static function createOverlappingDateRangeFilter(DateRange $range)
    {
        return function ($booking) use ($range) {
            $bookingRange = new DateRange(
                $booking->start, $booking->end
            );

            return $range->overlaps($bookingRange);
        };
    }
}
