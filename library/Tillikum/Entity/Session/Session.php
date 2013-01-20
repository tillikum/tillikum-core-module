<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Session;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="tillikum_session", indexes={
 *     @ORM\Index(name="idx_name", columns={"name"}),
 *     @ORM\Index(name="idx_modified", columns={"modified"}),
 * })
 */
class Session extends Entity
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
     * @ORM\Column(type="blob")
     */
    protected $data;

    /**
     * @ORM\Column(type="integer")
     */
    protected $lifetime;

    /**
     * @ORM\Column(type="integer")
     */
    protected $modified;
}
