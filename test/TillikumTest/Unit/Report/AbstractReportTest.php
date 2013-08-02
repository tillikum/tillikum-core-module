<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace TillikumTest\Unit\Report;

use ArrayObject;
use ReflectionProperty;

class AbstractReportTest extends \PHPUnit_Framework_TestCase
{
    protected $report;

    public function setUp()
    {
        $this->report = $this->getMockForAbstractClass(
            'Tillikum\Report\AbstractReport'
        );
    }

    public function testDefaultDescriptionIsTheEmptyString()
    {
        $this->assertEquals('', $this->report->getDescription());
    }

    public function testDefaultNameIsTheEmptyString()
    {
        $this->assertEquals('', $this->report->getName());
    }

    public function testGetParameters()
    {
        $parameters = array('foo' => 'bar');

        $refProperty = $this->getAccessibleReflectionProperty('parameters');
        $refProperty->setValue($this->report, $parameters);

        $this->assertEquals($parameters, $this->report->getParameters());
    }

    public function testGetParameter()
    {
        $parameters = array('foo' => 'bar');

        $refProperty = $this->getAccessibleReflectionProperty('parameters');
        $refProperty->setValue($this->report, $parameters);

        $this->assertNull($this->report->getParameter('quux'));
        $this->assertEquals('bar', $this->report->getParameter('foo'));
    }

    public function testSetParametersWithArray()
    {
        $parameters = array('foo' => 'bar');

        $refProperty = $this->getAccessibleReflectionProperty('parameters');

        $this->report->setParameters($parameters);
        $this->assertEquals($parameters, $refProperty->getValue($this->report));
    }

    public function testSetParametersWithArrayObject()
    {
        $parameters = array('foo' => 'bar');
        $arrayObject = new ArrayObject($parameters);

        $refProperty = $this->getAccessibleReflectionProperty('parameters');

        $this->report->setParameters($arrayObject);
        $this->assertEquals($parameters, $refProperty->getValue($this->report));
    }

    /**
     * @expectedException Tillikum\Report\Exception\InvalidArgumentException
     */
    public function testSetParametersThrowsExceptionOnInvalidInput()
    {
        $this->report->setParameters('foo');
    }

    protected function getAccessibleReflectionProperty($property)
    {
        $refProperty = new ReflectionProperty(
            get_class($this->report),
            $property
        );
        $refProperty->setAccessible(true);

        return $refProperty;
    }
}
