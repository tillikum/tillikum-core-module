<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Booking\Mealplan\Billing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Booking\Billing\AbstractBilling;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_booking_mealplan_billing")
 */
class Billing extends AbstractBilling
{
    /**
     * @ORM\OneToOne(targetEntity="Tillikum\Entity\Booking\Mealplan\Mealplan", inversedBy="billing")
     * @ORM\JoinColumn(name="booking_id", referencedColumnName="id")
     */
    protected $booking;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Booking\Mealplan\Billing\Rate\Rate", mappedBy="billing", cascade={"all"})
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $rates;

    public function __construct()
    {
        $this->rates = new ArrayCollection;
    }
}
