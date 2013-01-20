<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Billing\Event;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "tillikum_billing_event_adhoc" = "Tillikum\Entity\Billing\Event\AdHoc",
 *     "tillikum_billing_event_facilitybooking" = "Tillikum\Entity\Billing\Event\FacilityBooking",
 *     "tillikum_billing_event_mealplanbooking" = "Tillikum\Entity\Billing\Event\MealplanBooking"
 * })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("JOINED")
 * @ORM\Table(name="tillikum_billing_event")
 */
class Event extends Entity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Person\Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Billing\Rule\Rule")
     * @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
     */
    protected $rule;

    /**
     * @ORM\ManyToMany(targetEntity="Tillikum\Entity\Billing\Entry\Entry", inversedBy="events")
     * @ORM\JoinTable(
     *     name="tillikum_billing_event__entry",
     *     joinColumns={@ORM\JoinColumn(name="event_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="entry_id")}
     * )
     */
    protected $entries;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_processed;

    /**
     * @ORM\Column(type="utcdatetime")
     */
    protected $created_at;

    /**
     * @ORM\Column
     */
    protected $created_by;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersistListener()
    {
        foreach (array('created_at') as $attr) {
            if (!isset($this->$attr)) {
                $this->$attr = new DateTime();
            }
        }
    }
}
