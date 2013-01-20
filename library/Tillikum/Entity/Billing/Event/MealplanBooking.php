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

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_billing_event_mealplanbooking")
 */
class MealplanBooking extends AbstractBooking
{
    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Mealplan\Mealplan")
     * @ORM\JoinColumn(name="mealplan_id", referencedColumnName="id")
     */
    protected $mealplan;
}
