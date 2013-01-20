<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Facility\Config;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tillikum_facility_config", indexes={
 *     @ORM\Index(name="idx_name", columns={"name"}),
 *     @ORM\Index(name="idx_start", columns={"start"}),
 *     @ORM\Index(name="idx_end", columns={"end"}),
 *     @ORM\Index(name="idx_gender", columns={"gender"}),
 *     @ORM\Index(name="idx_capacity", columns={"capacity"})
 * })
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "tillikum_facility_config_config"="Config",
 *     "tillikum_facility_config_room_room"="Tillikum\Entity\Facility\Config\Room\Room"
 * })
 */
class Config extends Entity
{
    /**
     * @todo ZF2: Make this an annotation
     */
    const FORM_CLASS = 'Tillikum\Form\Facility\Config';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Facility\Facility", inversedBy="configs")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id")
     */
    protected $facility;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Billing\Rule\FacilityBooking")
     * @ORM\JoinColumn(name="default_billing_rule_id", referencedColumnName="id")
     */
    protected $default_billing_rule;

    /**
     * @ORM\Column
     */
    protected $name;

    /**
     * @ORM\Column(type="date")
     */
    protected $start;

    /**
     * @ORM\Column(type="date")
     */
    protected $end;

    /**
     * @ORM\Column
     */
    protected $gender;

    /**
     * @ORM\Column(type="integer")
     */
    protected $capacity;

    /**
     * @ORM\Column(type="text")
     */
    protected $note;

    /**
     * @ORM\Column(type="utcdatetime")
     */
    protected $created_at;

    /**
     * @ORM\Column
     */
    protected $created_by;

    /**
     * @ORM\Column(type="utcdatetime")
     */
    protected $updated_at;

    /**
     * @ORM\Column
     */
    protected $updated_by;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(
     *     name="tillikum_facility_config__tag",
     *     joinColumns={@ORM\JoinColumn(name="config_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id")}
     * )
     */
    protected $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersistListener()
    {
        foreach (array('created_at', 'updated_at') as $attr) {
            if (!isset($this->$attr)) {
                $this->$attr = new DateTime();
            }
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdateListener()
    {
        $this->updated_at = new DateTime();
    }
}
