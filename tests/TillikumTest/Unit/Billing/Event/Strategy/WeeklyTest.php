<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace TillikumTest\Unit\Billing\Event\Strategy;

use DateTime;
use Tillikum\Entity\Billing\Event\FacilityBooking as FacilityBookingEvent;
use Tillikum\Entity\Billing\Rule\Config\FacilityBooking as FacilityBookingRuleConfig;
use Tillikum\Billing\Event\Strategy\Weekly;

class WeeklyTest extends \PHPUnit_Framework_TestCase
{
    protected $ruleConfig;
    protected $strategy;

    public function setUp()
    {
        $this->ruleConfig = new FacilityBookingRuleConfig();
        $this->ruleConfig->amount = 10.00;
        $this->ruleConfig->currency = 'USD';
        $this->ruleConfig->code = 'test';

        $this->strategy = new Weekly();
    }

    public function testZeroDays()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-01-02');
        $event->end = new DateTime('2010-01-01');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(0, count($entries));
    }

    public function testOneDay()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-01-01');
        $event->end = new DateTime('2010-01-01');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(1, count($entries));

        $entry = $entries[0];

        $this->assertEquals('test', $entry->code);
        $this->assertEquals('USD', $entry->currency);
        $this->assertEquals(1.43, $entry->amount);
    }

    public function testOneWeek()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-01-01');
        $event->end = new DateTime('2010-01-07');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(1, count($entries));

        $entry = $entries[0];

        $this->assertEquals('test', $entry->code);
        $this->assertEquals('USD', $entry->currency);
        $this->assertEquals(10.00, $entry->amount);
    }

    public function testOneWeekAndAFewDays()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-01-01');
        $event->end = new DateTime('2010-01-10');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(2, count($entries));

        $entry = $entries[0];

        $this->assertEquals('test', $entry->code);
        $this->assertEquals('USD', $entry->currency);
        $this->assertEquals(10.00, $entry->amount);

        $entry = $entries[1];

        $this->assertEquals('test', $entry->code);
        $this->assertEquals('USD', $entry->currency);
        $this->assertEquals(4.29, $entry->amount);
    }
}
