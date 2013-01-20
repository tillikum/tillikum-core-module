<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Booking\Facility;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Booking\AbstractBooking;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_booking_facility", indexes={
 *     @ORM\Index(name="idx_start", columns={"start"}),
 *     @ORM\Index(name="idx_end", columns={"end"}),
 *     @ORM\Index(name="idx_checkin_at", columns={"checkin_at"}),
 *     @ORM\Index(name="idx_checkout_at", columns={"checkout_at"})
 * })
 */
class Facility extends AbstractBooking
{
    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Person\Person", inversedBy="bookings")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Facility\Facility", inversedBy="bookings")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id")
     */
    protected $facility;

    /**
     * @ORM\OneToOne(targetEntity="Tillikum\Entity\Booking\Facility\Billing\Billing", mappedBy="booking", cascade={"all"})
     */
    protected $billing;

    /**
     * @ORM\Column(type="utcdatetime", nullable=true)
     */
    protected $checkin_at;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $checkin_by;

    /**
     * @ORM\Column(type="utcdatetime", nullable=true)
     */
    protected $checkout_at;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $checkout_by;
}
