<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Specification\Specification;

use Tillikum\Specification\CompositeSpecification;

class GenderMatch extends CompositeSpecification
{
    protected $entity;
    protected static $ignoredGenders = array('U');

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function isSatisfiedBy($candidate)
    {
        if (in_array($candidate, self::$ignoredGenders)) {
            return true;
        }

        if (in_array($this->entity, self::$ignoredGenders)) {
            return true;
        }

        return $candidate == $this->entity;
    }
}
