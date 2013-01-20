<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Billing\Entry;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tillikum_billing_entry_post")
 */
class Post extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Tillikum\Entity\Billing\Entry\Entry", inversedBy="post")
     * @ORM\JoinColumn(name="entry_id", referencedColumnName="id")
     */
    protected $entry;

    /**
     * @ORM\Column
     */
    protected $description;

    /**
     * @ORM\Column(type="utcdatetime")
     */
    protected $created_at;

    /**
     * @ORM\Column
     */
    protected $created_by;

    /**
     * @ORM\PrePersist
     */
    public function prePersistListener()
    {
        foreach (array('created_at') as $attr) {
            if (!isset($this->$attr)) {
                $this->$attr = new DateTime();
            }
        }
    }
}
