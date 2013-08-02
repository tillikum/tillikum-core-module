<?php

namespace TillikumTest\Unit\Common\Booking;

use DateTime,
    \Vo\DateRange,
    \Doctrine\Common\Collections\ArrayCollection,
    \Tillikum\Common\Booking\Bookings;

class BookingsTest extends \PHPUnit_Framework_TestCase
{
    protected $a;

    public function setUp()
    {
        $this->a = new ArrayCollection;

        $b1 = new \Tillikum\Entity\Booking\Facility\Facility;
        $b1->start = new DateTime('2012-02-04');
        $b1->end = new DateTime('2012-03-31');

        $this->a[] = $b1;

        $b2 = new \Tillikum\Entity\Booking\Facility\Facility;
        $b2->start = new DateTime('2012-03-26');
        $b2->end = new DateTime('2012-06-20');

        $this->a[] = $b2;
    }

    public function testIncludedDateFilter()
    {
        $f = Bookings::createIncludedDateFilter(
            new DateTime('2012-04-05')
        );

        $this->assertEquals(1, count($this->a->filter($f)));

        $f = Bookings::createIncludedDateFilter(
            new DateTime('2012-07-05')
        );

        $this->assertEquals(0, count($this->a->filter($f)));
    }

    public function testIncludedDateRangeFilter()
    {
        $f = Bookings::createIncludedDateRangeFilter(
            new DateRange(
                new DateTime('2012-03-26'),
                new DateTime('2012-04-20')
            )
        );

        $this->assertEquals(0, count($this->a->filter($f)));

        $f = Bookings::createIncludedDateRangeFilter(
            new DateRange(
                new DateTime('2012-03-26'),
                new DateTime('2012-06-20')
            )
        );

        $this->assertEquals(1, count($this->a->filter($f)));
    }

    public function testOverlappingDateRangeFilter()
    {
        $f = Bookings::createOverlappingDateRangeFilter(
            new DateRange(
                new DateTime('2012-03-26'),
                new DateTime('2012-04-20')
            )
        );

        $this->assertEquals(2, count($this->a->filter($f)));

        $f = Bookings::createOverlappingDateRangeFilter(
            new DateRange(
                new DateTime('2012-04-01'),
                new DateTime('2012-06-20')
            )
        );

        $this->assertEquals(1, count($this->a->filter($f)));

        $f = Bookings::createOverlappingDateRangeFilter(
            new DateRange(
                new DateTime('2012-07-01'),
                new DateTime('2012-08-20')
            )
        );

        $this->assertEquals(0, count($this->a->filter($f)));
    }
}
