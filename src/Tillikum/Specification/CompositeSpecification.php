<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Specification;

abstract class CompositeSpecification implements Specification
{
    public function andSpec(self $specification)
    {
        return new AndSpecification($this, $specification);
    }

    public function orSpec(self $specification)
    {
        return new OrSpecification($this, $specification);
    }

    public function not()
    {
        return new NotSpecification($this);
    }
}
