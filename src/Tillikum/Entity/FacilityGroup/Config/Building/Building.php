<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\FacilityGroup\Config\Building;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\FacilityGroup\Config\Config;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_facilitygroup_config_building")
 */
class Building extends Config
{
    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\FacilityGroup\Building\Building", inversedBy="configs")
     * @ORM\JoinColumn(name="facilitygroup_id", referencedColumnName="id")
     */
    protected $facility_group;
}
