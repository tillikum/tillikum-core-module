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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tillikum_billing_entry")
 */
class Entry extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Billing\Invoice\Invoice", inversedBy="entries")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    protected $invoice;

    /**
     * @ORM\OneToOne(targetEntity="Tillikum\Entity\Billing\Entry\Post", mappedBy="entry")
     */
    protected $post;

    /**
     * @ORM\ManyToMany(targetEntity="Tillikum\Entity\Billing\Event\Event", mappedBy="entries")
     */
    protected $events;

    /**
     * @ORM\Column
     */
    protected $currency;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=4)
     */
    protected $amount;

    /**
     * @ORM\Column
     */
    protected $code;

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

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersistListener()
    {
        $this->created_at = new DateTime();
    }
}
