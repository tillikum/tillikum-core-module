<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace TillikumTest\Unit\Billing\Event\Processor;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Tillikum\Entity\Billing\Entry\Entry;
use Tillikum\Entity\Billing\Event\FacilityBooking as FacilityBookingEvent;
use Tillikum\Entity\Billing\Rule\FacilityBooking as FacilityBookingRule;
use Tillikum\Entity\Billing\Rule\Config\FacilityBooking as FacilityBookingRuleConfig;
use Tillikum\Billing\Event\Processor\BookingProcessor;
use Zend\Di;

class BookingProcessorTest extends \PHPUnit_Framework_TestCase
{
    protected $event;
    protected $mock;
    protected $processor;

    public function setUp()
    {
        $this->mock = $this->getMock(
            'Tillikum\Billing\Event\Strategy\StrategyInterface'
        );

        $di = new Di\Di();
        $im = $di->instanceManager();
        $im->addSharedInstance($this->mock, 'TestStrategyAlias');

        $this->processor = new BookingProcessor($di);

        $rule = new FacilityBookingRule();

        $config = new FacilityBookingRuleConfig();
        $config->amount = 10.00;
        $config->currency = 'USD';
        $config->start = new DateTime('2010-07-01');
        $config->end = new DateTime('2010-07-15');
        $config->strategy = 'TestStrategyAlias';
        $rule->configs->add($config);

        $config = new FacilityBookingRuleConfig();
        $config->amount = 20.00;
        $config->currency = 'USD';
        $config->start = new DateTime('2010-07-15');
        $config->end = new DateTime('2010-07-31');
        $config->strategy = 'TestStrategyAlias';
        $rule->configs->add($config);

        $this->event = new FacilityBookingEvent();
        $this->event->start = new DateTime('2010-07-01');
        $this->event->end = new DateTime('2010-07-15');
        $this->event->rule = $rule;
    }

    public function testHandlesEmptyDateRange()
    {
        $this->event->start = new DateTime('2010-07-02');
        $this->event->end = new DateTime('2010-07-01');

        $entries = $this->processor->process($this->event);

        $this->assertEquals(0, count($entries));
    }

    public function testHandlesSingleReturnedEntry()
    {
        $this->mock
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->event,
                $this->event->rule->configs[0]
            )
            ->will(
                $this->returnValue(
                    new ArrayCollection(
                        array(
                            new Entry(),
                        )
                    )
                )
            );

        $entries = $this->processor->process($this->event);

        foreach ($entries as $entry) {
            $this->assertEquals(1, count($entry->events));
            $this->assertSame($this->event, $entry->events[0]);
        }
    }

    public function testHandlesMultipleReturnedEntries()
    {
        $this->mock
             ->expects($this->once())
             ->method('process')
             ->with(
                 $this->event,
                 $this->event->rule->configs[0]
             )
             ->will(
                 $this->returnValue(
                     new ArrayCollection(
                         array(
                             new Entry(),
                             new Entry(),
                         )
                     )
                 )
             );

        $entries = $this->processor->process($this->event);

        foreach ($entries as $entry) {
            $this->assertEquals(1, count($entry->events));
            $this->assertSame($this->event, $entry->events[0]);
        }
    }

    public function testUsesLastEventRule()
    {
        $this->event->start = new DateTime('2010-07-15');
        $this->event->end = new DateTime('2010-07-31');

        $this->mock
            ->expects($this->once())
            ->method('process')
            ->with(
                $this->event,
                $this->event->rule->configs[1]
            )
            ->will(
                $this->returnValue(
                    new ArrayCollection(
                        array(
                            new Entry(),
                        )
                    )
                )
            );

        $this->processor->process($this->event);
    }

    /**
     * @expectedException Tillikum\Billing\Event\Processor\Exception\RuntimeException
     */
    public function testEventWithoutRuleThrowsException()
    {
        $this->event->rule = null;

        $this->processor->process($this->event);
    }

    /**
     * @expectedException Tillikum\Billing\Event\Processor\Exception\RuntimeException
     */
    public function testShortRuleRangeThrowsException()
    {
        $this->event->end = new DateTime('2010-07-16');

        $this->processor->process($this->event);
    }

    /**
     * @expectedException Tillikum\Billing\Event\Processor\Exception\RuntimeException
     */
    public function testRangeBeforeConfigurationThrowsException()
    {
        $this->event->start = new DateTime('2010-06-15');

        $this->processor->process($this->event);
    }

    /**
     * @expectedException Zend\Di\Exception\ClassNotFoundException
     */
    public function testLackOfStrategyThrowsException()
    {
        $this->event->rule->configs[0]->strategy = null;

        $this->processor->process($this->event);
    }
}
