<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Job;

use Tillikum\Entity\Job\Job as JobEntity;
use Traversable;

interface JobInterface
{
    /**
     * Run the job.
     *
     * @return null
     */
    public function run();

    /**
     * Return the description for this job.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Return the form class associated with this job.
     *
     * @return string
     */
    public function getFormClass();

    /**
     * Return the name for this job.
     *
     * @return string
     */
    public function getName();

    /**
     * @return null
     */
    public function clearParameters();

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param  string     $parameter
     * @return null|mixed
     */
    public function getParameter($parameter);

    /**
     * @param  array|Traversable $parameters
     * @return JobInterface
     */
    public function setParameters($parameters);

    /**
     * @return JobEntity
     */
    public function getJobEntity();

    /**
     * @param  JobEntity
     * @return JobInterface
     */
    public function setJobEntity(JobEntity $jobEntity);
}
