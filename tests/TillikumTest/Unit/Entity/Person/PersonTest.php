<?php

namespace TillikumTest\Unit\Entity\Person;

class PersonTest extends \PHPUnit_Framework_TestCase
{
    protected $p;

    public function setUp()
    {
        $this->p = new \Tillikum\Entity\Person\Person;
        $this->p->given_name = 'First';
        $this->p->middle_name = 'Middle';
        $this->p->family_name = 'Last';
    }

    public function testDisplayNameAssemblesFromParts()
    {
        $this->assertEquals('Last, First Middle', $this->p->display_name);
    }

    public function testDisplayNameOverridesParts()
    {
        $this->p->display_name = 'Test';

        $this->assertEquals('Test', $this->p->display_name);
    }
}
