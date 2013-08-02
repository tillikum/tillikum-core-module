<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Repository\Job;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use Tillikum\Entity\Job\Job as JobEntity;

class Job extends EntityRepository
{
    public function dequeueJob($jobId)
    {
        $em = $this->getEntityManager();

        $em->getConnection()->beginTransaction();

        $job = $em->find(
            'Tillikum\Entity\Job\Job',
            $jobId,
            LockMode::PESSIMISTIC_WRITE
        );

        if ($job->run_state !== JobEntity::RUN_STATE_SUBMITTED) {
            throw new \RuntimeException(
                'Job is already being processed.'
            );
        }

        $job->job_state = JobEntity::JOB_STATE_SUCCESS;
        $job->run_state = JobEntity::RUN_STATE_RUNNING;

        $em->persist($job);
        $em->flush();

        $em->getConnection()->commit();

        return $job;
    }
}
