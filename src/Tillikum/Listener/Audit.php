<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use SplObjectStorage;

class Audit implements EventSubscriber
{
    protected $identifier;

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::onFlush
        );
    }

    /**
     * @link http://docs.doctrine-project.org/en/latest/reference/events.html
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $primitivelyChangedEntities = new SplObjectStorage();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (property_exists($entity, 'created_by')) {
                $entity->created_by = $this->identifier;
                $primitivelyChangedEntities->attach($entity);
            }

            if (property_exists($entity, 'updated_by')) {
                $entity->updated_by = $this->identifier;
                $primitivelyChangedEntities->attach($entity);
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (property_exists($entity, 'updated_by')) {
                $entity->updated_by = $this->identifier;
                $primitivelyChangedEntities->attach($entity);
            }
        }

        foreach ($primitivelyChangedEntities as $entity) {
            $meta = $em->getClassMetadata(get_class($entity));
            $uow->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }
}
