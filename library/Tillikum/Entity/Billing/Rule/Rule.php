<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Billing\Rule;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "tillikum_billing_rule_adhoc" = "AdHoc",
 *     "tillikum_billing_rule_facilitybooking" = "FacilityBooking",
 *     "tillikum_billing_rule_mealplanbooking" = "MealplanBooking"
 * })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("JOINED")
 * @ORM\Table(name="tillikum_billing_rule")
 */
class Rule extends Entity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    protected $id;

    /**
     * @deprecated transitional only - do not use
     * @todo remove after CouchDB -> Tillikum migration
     * @ORM\Column
     */
    protected $old_id;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Billing\Rule\Config\Config", mappedBy="rule", cascade={"all"})
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $configs;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

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
        $this->configs = new ArrayCollection();
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
