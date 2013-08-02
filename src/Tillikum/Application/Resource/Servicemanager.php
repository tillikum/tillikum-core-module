<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Application\Resource;

use Zend\ServiceManager as ZendServiceManager;

class Servicemanager extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * Set up the service manager
     *
     * @return ServiceManager
     */
    public function init()
    {
        $options = $this->getOptions();

        $di = $this->getBootstrap()
            ->bootstrap('Di')
            ->getResource('Di');

        $doctrineContainer = $this->getBootstrap()
            ->bootstrap('Doctrine')
            ->getResource('Doctrine');

        $serviceManager = new ZendServiceManager\ServiceManager(
            new ZendServiceManager\Config($options)
        );

        $serviceManager->setService(
            'Di',
            $di
        );

        $serviceManager->setService(
            'doctrine.entitymanager.orm_default',
            $doctrineContainer->getEntityManager()
        );

        $serviceManager->addAbstractFactory(
            new ZendServiceManager\Di\DiAbstractServiceFactory($di)
        );

        return $serviceManager;
    }
}
