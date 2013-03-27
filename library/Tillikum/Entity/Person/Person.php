<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Person;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity(repositoryClass="Tillikum\Repository\Person\Person")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tillikum_person", indexes={
 *     @ORM\Index(name="idx_given_name", columns={"given_name"}),
 *     @ORM\Index(name="idx_family_name", columns={"family_name"}),
 *     @ORM\Index(name="idx_gender", columns={"gender"})
 * })
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "tillikum_person_person"="Tillikum\Entity\Person\Person"
 * })
 */
class Person extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Tillikum\ORM\Id\RandomUuid")
     */
    protected $id;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $given_name;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $middle_name;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $family_name;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $display_name;

    /**
     * @ORM\Column(nullable=true, type="date")
     */
    protected $birthdate;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $gender;

    /**
     * @ORM\Column(nullable=true, type="text")
     */
    protected $note;

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
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Person\Address\Street", mappedBy="person", cascade={"all"})
     */
    protected $addresses;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Booking\Facility\Facility", mappedBy="person", cascade={"all"})
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $bookings;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Contract\Signature", mappedBy="person", cascade={"all"})
     */
    protected $contract_signatures;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Person\Address\Email", mappedBy="person", cascade={"all"})
     */
    protected $emails;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Person\Address\EmergencyContact", mappedBy="person", cascade={"all"})
     */
    protected $emergency_contacts;

    /**
     * @ORM\OneToOne(targetEntity="Image", mappedBy="person", cascade={"all"})
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Billing\Invoice\Invoice", mappedBy="person", cascade={"all"})
     */
    protected $invoices;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Booking\Mealplan\Mealplan", mappedBy="person", cascade={"all"})
     * @ORM\OrderBy({"start"="ASC"})
     */
    protected $mealplans;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Person\Address\PhoneNumber", mappedBy="person", cascade={"all"})
     */
    protected $phone_numbers;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Person\Relation\Relation", mappedBy="head", cascade={"all"})
     */
    protected $relations;

    /**
     * @ORM\ManyToMany(targetEntity="Tillikum\Entity\Person\Tag")
     * @ORM\JoinTable(
     *     name="tillikum_person__tag",
     *     joinColumns={@ORM\JoinColumn(name="person_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id")}
     * )
     */
    protected $tags;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->contract_signatures = new ArrayCollection();
        $this->emails = new ArrayCollection();
        $this->emergency_contacts = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->mealplans = new ArrayCollection();
        $this->phone_numbers = new ArrayCollection();
        $this->relations = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersistListener()
    {
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdateListener()
    {
        $this->updated_at = new DateTime();
    }

    /**
     * Determines a person’s age from their date of birth
     *
     * If passed, the first parameter will be used as the assumed "current" day
     * with regard to the calculation. Otherwise, the current day will be used.
     *
     * If no date of birth exists, NAN will be returned.
     *
     * @param $date Current date for the calculation
     * @return int|NAN
     */
    public function getAge(DateTime $date = null)
    {
        if (($birthdate = $this->birthdate) === null) {
            return NAN;
        }

        $date = $date ?: new DateTime(date('Y-m-d'));

        return (int) $birthdate->diff($date)->y;
    }

    /**
     * Fetch the user’s display name, if it exists
     *
     * If the display name does not exist, it will format the user’s name as
     * "family_name, given_name middle_name".
     *
     * @return string
     */
    public function getDisplayName()
    {
        if ($this->display_name !== null) {
            return $this->display_name;
        }

        return sprintf(
            '%s%s%s',
            $this->family_name ?: '',
            $this->given_name ? ', ' . $this->given_name : '',
            $this->middle_name ? ' ' . $this->middle_name : ''
        );
    }
}
