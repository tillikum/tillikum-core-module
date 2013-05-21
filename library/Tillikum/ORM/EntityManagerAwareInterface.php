<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\ORM;

use Doctrine\ORM\EntityManager;

interface EntityManagerAwareInterface
{
    /**
     * Set the entity manager
     *
     * @return EntityManager
     */
    public function setEntityManager(EntityManager $entityManager);
}
