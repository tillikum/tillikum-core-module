<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Billing\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_billing_rule_adhoc")
 */
class AdHoc extends Rule
{
    const FORM_CLASS = 'Tillikum\Form\Billing\Rule';

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Billing\Rule\Config\AdHoc", mappedBy="rule", cascade={"all"})
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $configs;
}
