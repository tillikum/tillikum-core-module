<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Mealplan;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_mealplan", indexes={
 *    @ORM\Index(name="idx_is_active", columns={"is_active"})
 * })
 */
class Mealplan extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column
     */
    protected $id;

    /**
     * @ORM\Column
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Billing\Rule\MealplanBooking")
     * @ORM\JoinColumn(name="default_billing_rule_id", referencedColumnName="id")
     */
    protected $default_billing_rule;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_active;
}
