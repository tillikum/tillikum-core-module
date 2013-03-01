<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Person\File;

use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tillikum_person_file")
 */
class File extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Person\Person", inversedBy="files")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    /**
     * @ORM\ManyToOne(targetEntity="Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $type;

    /**
     * @ORM\Column
     */
    protected $hash;

    /**
     * @ORM\Column(type="utcdatetime")
     */
    protected $created_at;

    /**
     * @ORM\Column
     */
    protected $created_by;

    /**
     * @ORM\Column(type="utcdatetime")
     */
    protected $updated_at;

    /**
     * @ORM\Column
     */
    protected $updated_by;

    /**
     * @ORM\PrePersist
     */
    public function prePersistListener()
    {
        foreach (array('created_at', 'updated_at') as $attr) {
            if (!isset($this->$attr)) {
                $this->$attr = new DateTime();
            }
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdateListener()
    {
        $this->updated_at = new DateTime();
    }
}
