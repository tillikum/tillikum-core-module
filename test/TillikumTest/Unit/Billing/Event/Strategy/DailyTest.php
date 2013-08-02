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
use Tillikum\Billing\Event\Strategy\Daily;

class DailyTest extends \PHPUnit_Framework_TestCase
{
    protected $ruleConfig;
    protected $strategy;

    public function setUp()
    {
        $this->ruleConfig = new FacilityBookingRuleConfig();
        $this->ruleConfig->amount = 10.00;
        $this->ruleConfig->currency = 'USD';
        $this->ruleConfig->code = 'test';

        $this->strategy = new Daily();
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
        $this->assertEquals(10.00, $entry->amount);
    }

    public function testTwoDays()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-01-01');
        $event->end = new DateTime('2010-01-02');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(1, count($entries));

        $entry = $entries[0];

        $this->assertEquals('test', $entry->code);
        $this->assertEquals('USD', $entry->currency);
        $this->assertEquals(20.00, $entry->amount);
    }

    public function testManyDays()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-01-01');
        $event->end = new DateTime('2010-02-01');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(1, count($entries));

        $entry = $entries[0];

        $this->assertEquals('test', $entry->code);
        $this->assertEquals('USD', $entry->currency);
        $this->assertEquals(320.00, $entry->amount);
    }
}
