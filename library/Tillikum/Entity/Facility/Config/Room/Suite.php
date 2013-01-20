<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Facility\Config\Room;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_facility_config_room_suite")
 */
class Suite extends Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column
     */
    protected $name;
}
