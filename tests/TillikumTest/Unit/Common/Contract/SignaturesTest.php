<?php

namespace TillikumTest\Unit\Common\Contract;

use DateTime,
    \Vo\DateRange,
    \Doctrine\Common\Collections\ArrayCollection,
    \Tillikum\Common\Contract\Signatures;

class SignaturesTest extends \PHPUnit_Framework_TestCase
{
    protected $a;

    public function setUp()
    {
        $this->a = new ArrayCollection;

        $cancelled = new \Tillikum\Entity\Contract\Signature;
        $cancelled->is_signed = true;
        $cancelled->is_cancelled = true;
        $cancelled->requires_cosigned = true;
        $cancelled->is_cosigned = true;

        $this->a[] = $cancelled;

        $valid = new \Tillikum\Entity\Contract\Signature;
        $valid->is_signed = true;
        $valid->is_cancelled = false;
        $valid->requires_cosigned = true;
        $valid->is_cosigned = true;

        $this->a[] = $valid;

        $notCosigned = new \Tillikum\Entity\Contract\Signature;
        $notCosigned->is_signed = true;
        $notCosigned->is_cancelled = false;
        $notCosigned->requires_cosigned = true;
        $notCosigned->is_cosigned = false;

        $this->a[] = $notCosigned;
    }

    public function testIsActiveFilter()
    {
        $f = Signatures::createIsActiveFilter();

        $this->assertEquals(2, count($this->a->filter($f)));
    }

    public function testAreValid()
    {
        $this->assertTrue(Signatures::areValid($this->a));

        $this->a[1]->is_cosigned = false;

        $this->assertFalse(Signatures::areValid($this->a));
    }
}
