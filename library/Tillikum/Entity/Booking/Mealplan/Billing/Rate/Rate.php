<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Booking\Mealplan\Billing\Rate;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Booking\Billing\Rate\AbstractRate;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_booking_mealplan_billing_rate", indexes={
 *     @ORM\Index(name="idx_start", columns={"start"}),
 *     @ORM\Index(name="idx_end", columns={"end"})
 * })
 */
class Rate extends AbstractRate
{
    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Booking\Mealplan\Billing\Billing", inversedBy="rates")
     * @ORM\JoinColumn(name="billing_id", referencedColumnName="id")
     */
    protected $billing;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Billing\Rule\MealplanBooking")
     * @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
     */
    protected $rule;
}
