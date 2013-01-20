<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Specification;

class NotSpecification extends CompositeSpecification
{
    protected $spec;

    public function __construct(Specification $spec)
    {
        $this->spec = $spec;
    }

    public function isSatisfiedBy($candidate)
    {
        return !$this->spec->isSatisfiedBy($candidate);
    }
}
