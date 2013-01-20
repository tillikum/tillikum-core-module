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
 * @ORM\Table(name="tillikum_facility_config_room_type", indexes={
 *     @ORM\Index(name="idx_is_active", columns={"is_active"})
 * })
 */
class Type extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column
     */
    protected $id;

    /**
     * @ORM\Column
     */
    protected $name;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_active;
}
