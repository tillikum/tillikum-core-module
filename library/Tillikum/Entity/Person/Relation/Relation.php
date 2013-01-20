<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Person\Relation;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * A unidirectional relationship between two people.
 *
 * @ORM\Entity
 * @ORM\Table(name="tillikum_person_relation")
 */
class Relation extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Person\Relation\Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Person\Person", inversedBy="relations")
     * @ORM\JoinColumn(name="head_id", referencedColumnName="id")
     */
    protected $head;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Person\Person")
     * @ORM\JoinColumn(name="tail_id", referencedColumnName="id")
     */
    protected $tail;
}
