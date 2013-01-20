<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Person\Address;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tillikum_person_address_emergencycontact")
 */
class EmergencyContact extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tillikum\Entity\Person\Person", inversedBy="emergency_contacts")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    /**
     * @ORM\ManyToOne(targetEntity="Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    protected $type;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $given_name;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $family_name;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $relationship;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $primary_phone_number;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $secondary_phone_number;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $primary_email;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $secondary_email;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $street;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $locality;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $region;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $postal_code;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $country;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_primary;

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
