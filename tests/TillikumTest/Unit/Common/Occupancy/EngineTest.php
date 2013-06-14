<?php

namespace TillikumTest\Unit\Common\Occupancy;

use DateTime;
use Tillikum\Common\Occupancy;

class EngineTest extends \PHPUnit_Framework_TestCase
{
    public function testSucceedsWithNoInputs()
    {
        $e = new Occupancy\Engine(array());

        $this->assertTrue($e->run()->getIsSuccess());
    }

    /**
     * @expectedException Tillikum\Common\Occupancy\Exception\InvalidArgumentException
     */
    public function testBadInputThrowsException()
    {
        $e = new Occupancy\Engine(array(new \stdClass()));
    }

    public function testSucceedsWithContainedDateRange()
    {
        $inputs = array(
            new Occupancy\Input(
                new DateTime('2010-01-01'),
                1,
                'config 1 start'
            ),
            new Occupancy\Input(
                new DateTime('2010-01-02'),
                -1,
                'booking 1 start'
            ),
            new Occupancy\Input(
                new DateTime('2010-01-08'),
                1,
                'booking 1 end'
            ),
        );

        $e = new Occupancy\Engine($inputs);

        $this->assertTrue($e->run()->getIsSuccess());
    }

    public function testSucceedsWithMultipleBookingsAndConfigurationIncrease()
    {
        $inputs = array(
            new Occupancy\Input(
                new DateTime('2010-04-01'),
                1,
                'config 1 start'
            ),
            new Occupancy\Input(
                new DateTime('2012-12-15'),
                1,
                'config 2 start'
            ),
            new Occupancy\Input(
                new DateTime('2012-09-13'),
                -1,
                'booking 1 start'
            ),
            new Occupancy\Input(
                new DateTime('2013-06-14'),
                1,
                'booking 1 end'
            ),
            new Occupancy\Input(
                new DateTime('2012-12-30'),
                -1,
                'booking 2 start'
            ),
            new Occupancy\Input(
                new DateTime('2013-02-09'),
                1,
                'booking 2 end'
            ),
        );

        $e = new Occupancy\Engine($inputs);

        $this->assertTrue($e->run()->getIsSuccess());
    }

    public function testFailsWithMultipleBookingsAndInsufficientConfiguredSpace()
    {
        $inputs = array(
            new Occupancy\Input(
                new DateTime('2010-04-01'),
                1,
                'config 1 start'
            ),
            new Occupancy\Input(
                new DateTime('2012-09-13'),
                -1,
                'booking 1 start'
            ),
            new Occupancy\Input(
                new DateTime('2013-06-14'),
                1,
                'booking 1 end'
            ),
            new Occupancy\Input(
                new DateTime('2012-12-30'),
                -1,
                'booking 2 start'
            ),
            new Occupancy\Input(
                new DateTime('2013-02-09'),
                1,
                'booking 2 end'
            ),
        );

        $e = new Occupancy\Engine($inputs);

        $result = $e->run();

        $this->assertFalse($result->getIsSuccess());
        $this->assertEquals('booking 2 start', $result->getCulprit()->getDescription());
    }
}
