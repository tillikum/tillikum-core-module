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
use Tillikum\Entity\Facility\Config\Config;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_facility_config_room", indexes={
 *     @ORM\Index(name="idx_floor", columns={"floor"}),
 *     @ORM\Index(name="idx_section", columns={"section"})
 * })
 */
class Room extends Config
{
    /**
     * @todo ZF2: Make this an annotation
     */
    const FORM_CLASS = 'Tillikum\Form\Facility\RoomConfig';

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Facility\Room\Room", inversedBy="configs")
     * @ORM\JoinColumn(name="facility_id", referencedColumnName="id")
     */
    protected $facility;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Facility\Config\Room\Suite")
     * @ORM\JoinColumn(name="suite_id", nullable=true, referencedColumnName="id")
     */
    protected $suite;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Facility\Config\Room\Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $type;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $floor;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $section;
}
