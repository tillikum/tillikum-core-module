<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\FacilityGroup\Building;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\FacilityGroup\FacilityGroup;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_facilitygroup_building")
 */
class Building extends FacilityGroup
{
    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\FacilityGroup\Config\Building\Building", mappedBy="facility_group", cascade={"all"})
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $configs;
}
