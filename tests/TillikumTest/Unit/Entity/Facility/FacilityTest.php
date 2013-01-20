<?php

namespace TillikumTest\Unit\Entity\Facility;

use DateTime;

class FacilityTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->f = new \Tillikum\Entity\Facility\Facility;

        $fg = new \Tillikum\Entity\FacilityGroup\FacilityGroup;

        $gc1 = new \Tillikum\Entity\FacilityGroup\Config\Config;
        $gc1->name = 'Group 1';
        $gc1->start = new \DateTime('2009-01-01');
        $gc1->end = new \DateTime('2010-11-01');

        $fg->configs[] = $gc1;

        $this->f->facility_group = $fg;

        $c1 = new \Tillikum\Entity\Facility\Config\Config;
        $c1->name = 'Config 1';
        $c1->start = new \DateTime('2009-01-01');
        $c1->end = new \DateTime('2009-12-31');

        $this->f->configs[] = $c1;

        $c2 = new \Tillikum\Entity\Facility\Config\Config;
        $c2->name = 'Config 2';
        $c2->start = new \DateTime('2010-01-01');
        $c2->end = new \DateTime('2010-12-31');

        $this->f->configs[] = $c2;
    }

    public function testGetConfigOnDate()
    {
        $this->assertEquals('Config 1', $this->f->getConfigOnDate(new DateTime('2009-01-01'))->name);
        $this->assertEquals('Config 1', $this->f->getConfigOnDate(new DateTime('2009-12-31'))->name);
        $this->assertEquals('Config 2', $this->f->getConfigOnDate(new DateTime('2010-01-01'))->name);

        $this->assertNull($this->f->getConfigOnDate(new DateTime('2013-01-01')));
        $this->assertNull($this->f->getConfigOnDate(new DateTime('2008-12-31')));
    }

    public function testGetNamesOnDate()
    {
        $this->assertEquals(array('Group 1', 'Config 1'), $this->f->getNamesOnDate(new DateTime('2009-01-01')));
        $this->assertEquals(array('?', '?'), $this->f->getNamesOnDate(new DateTime('2008-12-31')));
        $this->assertEquals(array('?', 'Config 2'), $this->f->getNamesOnDate(new DateTime('2010-12-31')));
    }
}
