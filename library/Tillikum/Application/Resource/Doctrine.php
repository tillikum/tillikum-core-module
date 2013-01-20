<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Application\Resource;

use Bisna\Doctrine\Container;
use Doctrine\ORM\Proxy\Autoloader as ProxyAutoloader;

class Doctrine extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Container
     */
    protected $container;

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

        $this->container = new Container($options);

        return $this->container;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
