<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Billing\Rule\Config;

use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\MappedSuperclass
 */
class AbstractBooking extends Config
{
    /**
     * @ORM\Column
     */
    protected $currency;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=4)
     */
    protected $amount;
}
