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
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

/**
 * Provides metadata loading for site-specific dynamic tables
 */
class ExtensionMetadata implements EventSubscriber
{
    protected $entityClassNames;

    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        $driver = $eventArgs->getEntityManager()
            ->getConfiguration()
            ->getMetadataDriverImpl();

        if ($metadata->isInheritanceTypeNone()) {
            return;
        }

        $discriminatorMap = $metadata->discriminatorMap;
        foreach ($this->getChildClasses($driver, $metadata->name) as $className) {
            if (!in_array($className, $discriminatorMap)) {
                $discriminatorName = strtolower(
                    str_replace(
                        array('\\Entity', '\\'),
                        array('', '_'),
                        $className
                    )
                );

                $discriminatorMap[$discriminatorName] = $className;
            }
        }

        $metadata->setDiscriminatorMap($discriminatorMap);
    }

    protected function getChildClasses(MappingDriver $driver, $currentClass)
    {
        $classes = array();
        foreach ($driver->getAllClassNames() as $className) {
            if (!is_subclass_of($className, $currentClass)) {
                continue;
            }

            $classes[] = $className;
        }

        return $classes;
    }
}
