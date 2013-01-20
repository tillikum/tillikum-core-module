<?php

namespace TillikumTest\Unit\Entity\FacilityGroup;

use DateTime;

class FacilityGroupTest extends \PHPUnit_Framework_TestCase
{
    protected $fg;

    public function setUp()
    {
        $fg = new \Tillikum\Entity\FacilityGroup\FacilityGroup;

        $gc1 = new \Tillikum\Entity\FacilityGroup\Config\Config;
        $gc1->name = 'Group 1';
        $gc1->start = new \DateTime('2009-01-01');
        $gc1->end = new \DateTime('2010-11-01');

        $fg->configs[] = $gc1;

        $this->fg = $fg;
    }

    public function testGetConfigOnDate()
    {
        $this->assertEquals('Group 1', $this->fg->getConfigOnDate(new DateTime('2009-01-01'))->name);
        $this->assertEquals('Group 1', $this->fg->getConfigOnDate(new DateTime('2009-01-02'))->name);
        $this->assertEquals('Group 1', $this->fg->getConfigOnDate(new DateTime('2010-11-01'))->name);

        $this->assertNull($this->fg->getConfigOnDate(new DateTime('2012-03-01')));
        $this->assertNull($this->fg->getConfigOnDate(new DateTime('2008-12-31')));
    }
}
