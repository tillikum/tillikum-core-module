<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\FacilityGroup\Config;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "tillikum_facilitygroup_config_config"="Config",
 *     "tillikum_facilitygroup_config_building_building"="Tillikum\Entity\FacilityGroup\Config\Building\Building"
 * })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("JOINED")
 * @ORM\Table(name="tillikum_facilitygroup_config", indexes={
 *     @ORM\Index(name="idx_name", columns={"name"}),
 *     @ORM\Index(name="idx_start", columns={"start"}),
 *     @ORM\Index(name="idx_end", columns={"end"}),
 *     @ORM\Index(name="idx_gender", columns={"gender"})
 * })
 */
class Config extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\FacilityGroup\FacilityGroup", inversedBy="configs")
     * @ORM\JoinColumn(name="facilitygroup_id", referencedColumnName="id")
     */
    protected $facility_group;

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
     * @ORM\PrePersist
     */
    public function prePersistListener()
    {
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdateListener()
    {
        $this->updated_at = new DateTime();
    }
}
