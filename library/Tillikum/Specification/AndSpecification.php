<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Specification;

class AndSpecification extends CompositeSpecification
{
    protected $specA;
    protected $specB;

    public function __construct(Specification $specA, Specification $specB)
    {
        $this->specA = $specA;
        $this->specB = $specB;
    }

    public function isSatisfiedBy($candidate)
    {
        return $this->specA->isSatisfiedBy($candidate) && $this->specB->isSatisfiedBy($candidate);
    }
}
