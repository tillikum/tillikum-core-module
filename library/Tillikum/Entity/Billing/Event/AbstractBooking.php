<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Billing\Event;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractBooking extends Event
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_credit;

    /**
     * @ORM\Column(type="date")
     */
    protected $start;

    /**
     * @ORM\Column(type="date")
     */
    protected $end;
}
