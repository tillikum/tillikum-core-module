<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Billing\Rule\Config;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "tillikum_billing_rule_config_adhoc" = "AdHoc",
 *     "tillikum_billing_rule_config_facilitybooking" = "FacilityBooking",
 *     "tillikum_billing_rule_config_mealplanbooking" = "MealplanBooking"
 * })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("JOINED")
 * @ORM\Table(name="tillikum_billing_rule_config")
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
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Billing\Rule\Rule", inversedBy="configs")
     * @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
     */
    protected $rule;

    /**
     * @ORM\Column
     */
    protected $strategy;

    /**
     * @ORM\Column
     */
    protected $code;

    /**
     * @ORM\Column
     */
    protected $description;

    /**
     * @ORM\Column(type="date")
     */
    protected $start;

    /**
     * @ORM\Column(type="date")
     */
    protected $end;

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
