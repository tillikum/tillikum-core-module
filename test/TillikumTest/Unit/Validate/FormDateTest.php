<?php

namespace TillikumTest\Unit\Validate;

use Tillikum\Validate\FormDate as FormDate,
    PHPUnit_Framework_TestCase as TestCase;

class FormDateTest extends TestCase
{
    protected $validator;

    public function setUp()
    {
        $this->validator = new FormDate;
    }

    public function testValidIso8601DatePasses()
    {
        $this->assertTrue($this->validator->isValid('2011-02-03'));
    }

    public function testInvalidIso8601DateFails()
    {
        $this->assertFalse($this->validator->isValid('2011-02-32'));
    }

    public function testValidNonIsoDateFails()
    {
        $this->assertFalse($this->validator->isValid('2/25/2011'));
    }
}
