<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Application\Resource;

use Bisna\Application\Resource\Doctrine as BisnaDoctrineResource;
use Bisna\Doctrine\Container;
use Doctrine\ORM\Proxy\Autoloader as ProxyAutoloader;
use Tillikum\Listener\ExtensionMetadata as ExtensionMetadataListener;

class Doctrine extends BisnaDoctrineResource
{
    /**
     * @return Container
     */
    public function init()
    {
        $options = $this->getOptions();

        foreach ($options['orm']['entityManagers'] as $k => $v) {
            if (!isset($v['proxy'])) {
                continue;
            }

            $autoloader = ProxyAutoloader::register(
                $v['proxy']['dir'],
                $v['proxy']['namespace']
            );
        }

        $container = parent::init();

        $entityManager = $container->getEntityManager();
        $eventManager = $entityManager->getEventManager();

        $eventManager->addEventSubscriber(new ExtensionMetadataListener());

        return $container;
    }
}
