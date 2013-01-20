<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Facility;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;
use Vo\DateRange;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tillikum_facility")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "tillikum_facility_facility"="Facility",
 *     "tillikum_facility_room_room"="Tillikum\Entity\Facility\Room\Room"
 * })
 */
class Facility extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Tillikum\ORM\Id\RandomUuid")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Booking\Facility\Facility", mappedBy="facility")
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $bookings;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Facility\Hold\Hold", mappedBy="facility", cascade={"all"})
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $holds;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Facility\Config\Config", mappedBy="facility", cascade={"all"})
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $configs;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\FacilityGroup\FacilityGroup", inversedBy="facilities")
     * @ORM\JoinColumn(name="facilitygroup_id", referencedColumnName="id")
     */
    protected $facility_group;

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
        $this->bookings = new ArrayCollection;
        $this->holds = new ArrayCollection;
        $this->configs = new ArrayCollection;
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

    /**
     * Get the facility bookings valid on the given date, if any
     *
     * @return \Tillikum\Entity\Booking\Facility\Facility[]
     */
    public function getBookingsOnDate(DateTime $date)
    {
        return $this->bookings->filter(
            function ($booking) use ($date) {
                $bookingRange = new DateRange($booking->start, $booking->end);

                return $bookingRange->includes($date);
            }
        )
            ->getValues();
    }

    /**
     * Get the configuration valid on the given date, if any
     *
     * @return Config\Config|null
     */
    public function getConfigOnDate(DateTime $date)
    {
        $ret = $this->configs->filter(
            function ($config) use ($date) {
                $configRange = new DateRange($config->start, $config->end);

                return $configRange->includes($date);
            }
        )
            ->first();

        return $ret === false ? null : $ret;
    }

    /**
     * Get the hold valid on the given date, if any
     *
     * @return Hold\Hold|null
     */
    public function getHoldOnDate(DateTime $date)
    {
        $ret = $this->holds->filter(
            function ($hold) use ($date) {
                $holdRange = new DateRange($hold->start, $hold->end);

                return $holdRange->includes($date);
            }
        )
            ->first();

        return $ret === false ? null : $ret;
    }

    /**
     * Get the facility bookings valid on today’s date, if any
     *
     * @return \Tillikum\Entity\Booking\Facility\Facility[]
     */
    public function getCurrentBookings()
    {
        return $this->getBookingsOnDate(new DateTime(date('Y-m-d')));
    }

    /**
     * Get the configuration valid on today’s date, if any
     *
     * @return Config\Config|null
     */
    public function getCurrentConfig()
    {
        return $this->getConfigOnDate(new DateTime(date('Y-m-d')));
    }

    /**
     * Get the hold valid on today’s date, if any
     *
     * @return Hold\Hold|null
     */
    public function getCurrentHold()
    {
        return $this->getHoldOnDate(new DateTime(date('Y-m-d')));
    }

    /**
     * Get an array of current facility and facility group names
     *
     * Returns a 2-element array containing '?' in each element if no
     * configuration was found.
     *
     * @return array 2-element array ('groupName', 'facilityName') with '?'
     *               placeholders for elements that don't exist.
     */
    public function getCurrentNames()
    {
        return $this->getNamesOnDate(new DateTime(date('Y-m-d')));
    }

    /**
     * Get an array of facility and facility group names on the given date
     *
     * @param  DateTime $date Date to test configurations against
     * @return array    2-element array ('groupName', 'facilityName') with
     *                  '?' placeholders for elements that don't exist.
     */
    public function getNamesOnDate(DateTime $date)
    {
        $facilityGroupConfig = $this->facility_group->getConfigOnDate($date);
        $facilityConfig = $this->getConfigOnDate($date);

        $ret = array(
            $facilityGroupConfig === null ? '?' : $facilityGroupConfig->name,
            $facilityConfig === null ? '?' : $facilityConfig->name,
        );

        return $ret;
    }
}
