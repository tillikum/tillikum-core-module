<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Entity\Job;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Tillikum\Entity\Entity;

/**
 * @ORM\Entity(repositoryClass="Tillikum\Repository\Job\Job")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tillikum_job")
 */
class Job extends Entity
{
    const RUN_STATE_SUBMITTED = 'submitted';
    const RUN_STATE_RUNNING = 'running';
    const RUN_STATE_STOPPED = 'stopped';

    const JOB_STATE_SUCCESS = 'success';
    const JOB_STATE_WARNING = 'warning';
    const JOB_STATE_ERROR = 'error';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Job\Parameter\Parameter", mappedBy="job", cascade={"all"})
     */
    protected $parameters;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Job\Message\Message", mappedBy="job", cascade={"all"})
     */
    protected $messages;

    /**
     * @ORM\OneToMany(targetEntity="Tillikum\Entity\Job\Attachment\Attachment", mappedBy="job", cascade={"all"})
     */
    protected $attachments;

    /**
     * @ORM\Column
     */
    protected $class_name;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_dry_run;

    /**
     * @ORM\Column
     */
    protected $job_state;

    /**
     * @ORM\Column
     */
    protected $run_state;

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

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->parameters = new ArrayCollection();
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
}
