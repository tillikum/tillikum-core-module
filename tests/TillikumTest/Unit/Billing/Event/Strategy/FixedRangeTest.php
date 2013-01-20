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
use Tillikum\Billing\Event\Strategy\FixedRange;

class FixedRangeTest extends \PHPUnit_Framework_TestCase
{
    protected $ruleConfig;
    protected $strategy;

    public function setUp()
    {
        $this->ruleConfig = new FacilityBookingRuleConfig();
        $this->ruleConfig->start = new DateTime('2010-07-01');
        $this->ruleConfig->end = new DateTime('2010-07-31');
        $this->ruleConfig->amount = 10.00;
        $this->ruleConfig->currency = 'USD';
        $this->ruleConfig->code = 'test';

        $this->strategy = new FixedRange();
    }

    public function testDateRangeMatchesExactly()
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

    public function testMustProrate()
    {
        $event = new FacilityBookingEvent();
        $event->start = new DateTime('2010-07-01');
        $event->end = new DateTime('2010-07-15');

        $entries = $this->strategy->process(
            $event,
            $this->ruleConfig
        );

        $this->assertEquals(1, count($entries));

        $this->assertEquals('test', $entries[0]->code);
        $this->assertEquals(4.84, $entries[0]->amount);
        $this->assertEquals('USD', $entries[0]->currency);
    }
}
