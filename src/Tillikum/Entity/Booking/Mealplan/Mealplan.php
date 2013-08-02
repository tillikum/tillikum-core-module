<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Booking\Mealplan;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Booking\AbstractBooking;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_booking_mealplan", indexes={
 *     @ORM\Index(name="idx_start", columns={"start"}),
 *     @ORM\Index(name="idx_end", columns={"end"})
 * })
 */
class Mealplan extends AbstractBooking
{
    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Person\Person", inversedBy="mealplans")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Mealplan\Mealplan")
     * @ORM\JoinColumn(name="mealplan_id", referencedColumnName="id")
     */
    protected $mealplan;

    /**
     * @ORM\OneToOne(targetEntity="Tillikum\Entity\Booking\Mealplan\Billing\Billing", mappedBy="booking", cascade={"all"})
     */
    protected $billing;
}
