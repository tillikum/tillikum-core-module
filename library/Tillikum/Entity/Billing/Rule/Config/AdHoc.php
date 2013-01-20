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
 * @ORM\Table(name="tillikum_billing_rule_config_adhoc")
 */
class AdHoc extends Config
{
    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Billing\Rule\AdHoc", inversedBy="configs")
     * @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
     */
    protected $rule;
}
