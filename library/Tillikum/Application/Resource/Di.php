<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Application\Resource;

use Zend\Db;
use Zend\Di as ZendDi;

class Di extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * Set up dependency injection framework
     *
     * @return ServiceManager
     */
    public function init()
    {
        $options = $this->getOptions();

        $doctrineContainer = $this->getBootstrap()
            ->bootstrap('Doctrine')
            ->getResource('Doctrine');

        $di = new ZendDi\Di(
            null,
            null,
            new ZendDi\Config($options)
        );

        $di->instanceManager()->addSharedInstance(
            new Db\Adapter\Driver\Pdo\Pdo(
                $doctrineContainer->getEntityManager()
                    ->getConnection()
                    ->getWrappedConnection()
            ),
            'Zend\Db\Adapter\Driver\Pdo\Pdo'
        );

        $di->instanceManager()->addSharedInstance(
            $doctrineContainer->getEntityManager(),
            'Doctrine\ORM\EntityManager'
        );

        return $di;
    }
}
