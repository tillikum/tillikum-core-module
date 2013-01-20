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
use Tillikum\Billing\Event\Strategy\Monthly;

class MonthlyTest extends \PHPUnit_Framework_TestCase
{
    protected $ruleConfig;
    protected $strategy;

    public function setUp()
    {
        $this->ruleConfig = new FacilityBookingRuleConfig();
        $this->ruleConfig->amount = 10.00;
        $this->ruleConfig->currency = 'USD';
        $this->ruleConfig->code = 'test';

        $this->strategy = new Monthly();
    }

    public function testUnderOneMonth()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-06-01');
        $event->end = new DateTime('2010-06-10');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(1, count($entries));

        $this->assertEquals('test', $entries[0]->code);
        $this->assertEquals(3.33, $entries[0]->amount);
        $this->assertEquals('USD', $entries[0]->currency);
    }

    public function testOneMonth()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-07-01');
        $event->end = new DateTime('2010-07-31');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(1, count($entries));

        $this->assertEquals('test', $entries[0]->code);
        $this->assertEquals(10.00, $entries[0]->amount);
        $this->assertEquals('USD', $entries[0]->currency);
    }

    public function testOverOneMonth()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-07-01');
        $event->end = new DateTime('2010-08-15');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(2, count($entries));

        $this->assertEquals('test', $entries[0]->code);
        $this->assertEquals(10.00, $entries[0]->amount);
        $this->assertEquals('USD', $entries[0]->currency);

        $this->assertEquals('test', $entries[1]->code);
        $this->assertEquals(4.84, $entries[1]->amount);
        $this->assertEquals('USD', $entries[1]->currency);
    }

    public function testLeadingDaysAndTrailingDays()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-06-15');
        $event->end = new DateTime('2010-09-15');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(3, count($entries));

        $this->assertEquals('test', $entries[0]->code);
        $this->assertEquals(5.33, $entries[0]->amount);
        $this->assertEquals('USD', $entries[0]->currency);

        $this->assertEquals('test', $entries[1]->code);
        $this->assertEquals(20.00, $entries[1]->amount);
        $this->assertEquals('USD', $entries[1]->currency);

        $this->assertEquals('test', $entries[2]->code);
        $this->assertEquals(5.00, $entries[2]->amount);
        $this->assertEquals('USD', $entries[2]->currency);
    }
}
