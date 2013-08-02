<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\FacilityGroup;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;
use Vo\DateRange;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tillikum_facilitygroup")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "tillikum_facilitygroup_facilitygroup"="FacilityGroup",
 *     "tillikum_facilitygroup_building_building"="Tillikum\Entity\FacilityGroup\Building\Building"
 * })
 */
class FacilityGroup extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Tillikum\ORM\Id\RandomUuid")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Facility\Facility", mappedBy="facility_group")
     */
    protected $facilities;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\FacilityGroup\Config\Config", mappedBy="facility_group", cascade={"all"})
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $configs;

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

    public function __construct()
    {
        $this->configs = new ArrayCollection;
        $this->facilities = new ArrayCollection;
    }

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

    /**
     * Get the configuration valid on the given date, if any.
     *
     * @param  DateTime           $date Date to test configurations against
     * @return Config\Config|null Configuration on the passed date
     */
    public function getConfigOnDate(DateTime $date)
    {
        foreach ($this->configs as $config) {
            $configRange = new DateRange($config->start, $config->end);

            if ($configRange->includes($date)) {
                return $config;
            }
        }

        return null;
    }

    /**
     * Get the configuration valid on today’s date, if any.
     *
     * @return Config\Config|null Current configuration
     */
    public function getCurrentConfig()
    {
        return $this->getConfigOnDate(new DateTime(date('Y-m-d')));
    }
}
