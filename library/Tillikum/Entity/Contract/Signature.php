<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Contract;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_contract_signature", indexes={
 *     @ORM\Index(name="idx_requires_cosigned", columns={"requires_cosigned"}),
 *     @ORM\Index(name="idx_is_signed", columns={"is_signed"}),
 *     @ORM\Index(name="idx_signed_at", columns={"signed_at"}),
 *     @ORM\Index(name="idx_is_cosigned", columns={"is_cosigned"}),
 *     @ORM\Index(name="idx_cosigned_at", columns={"cosigned_at"}),
 *     @ORM\Index(name="idx_is_cancelled", columns={"is_cancelled"}),
 *     @ORM\Index(name="idx_cancelled_at", columns={"cancelled_at"}),
 * })
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "tillikum_contract_signature"="Tillikum\Entity\Contract\Signature"
 * })
 */
class Signature extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Person\Person", inversedBy="contract_signatures")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Contract\Contract")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     */
    protected $contract;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $requires_cosigned;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_signed;

    /**
     * @ORM\Column(nullable=true, type="utcdatetime")
     */
    protected $signed_at;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $signed_by;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_cosigned;

    /**
     * @ORM\Column(nullable=true, type="utcdatetime")
     */
    protected $cosigned_at;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $cosigned_by;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_cancelled;

    /**
     * @ORM\Column(nullable=true, type="utcdatetime")
     */
    protected $cancelled_at;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $cancelled_by;
}
