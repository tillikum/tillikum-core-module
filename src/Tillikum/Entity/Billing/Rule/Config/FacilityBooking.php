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
 * @ORM\Entity
 * @ORM\Table(name="tillikum_billing_rule_config_facilitybooking")
 */
class FacilityBooking extends AbstractBooking
{
    const FORM_CLASS = 'Tillikum\Form\Billing\Config\FacilityBooking';

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Billing\Rule\FacilityBooking", inversedBy="configs")
     * @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
     */
    protected $rule;
}
