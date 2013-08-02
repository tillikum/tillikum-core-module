<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Facility\Room;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Facility\Facility;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_facility_room")
 */
class Room extends Facility
{
    /**
     * @todo ZF2: Make this an annotation
     */
    const FORM_CLASS = 'Tillikum\Form\Facility\Room';

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Facility\Config\Room\Room", mappedBy="facility", cascade={"all"})
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $configs;
}
