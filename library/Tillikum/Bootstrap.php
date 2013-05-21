<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum;

use Zend\Log\Logger;
use Zend\Session;

/**
 * Perform global initialization tasks
 */
class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Set encoding defaults
     *
     * Currently hardcoded for UTF-8 because that is what we use internally.
     * This is not what we would change for outputting a different encoding.
     *
     * @return null
     */
    public function _initEncodingDefaults()
    {
        iconv_set_encoding('internal_encoding', 'UTF-8');
        mb_internal_encoding('UTF-8');
    }

    /**
     * Initialize Tillikum error handler
     *
     * Uses the defined logger to log errors.
     *
     * @return null
     */
    public function _initErrorHandler()
    {
        $this->bootstrap('Servicemanager');

        $sm = $this->getResource('Servicemanager');

        $logger = $sm->get('Logger');

        Logger::registerErrorHandler($logger);
    }

    /**
     * Initialize Tillikum exception handler
     *
     * Uses the defined logger to log uncaught exceptions.
     *
     * @return null
     */
    public function _initExceptionHandler()
    {
        $this->bootstrap('Servicemanager');

        $sm = $this->getResource('Servicemanager');

        $logger = $sm->get('Logger');

        Logger::registerExceptionHandler($logger);
    }

    /**
     * Set precision for bcmath
     *
     * Currently hardcoded to '16'.
     *
     * @return null
     */
    public function _initPrecision()
    {
        bcscale(16);
    }

    /**
     * Session manager initialization
     *
     * @return null
     */
    public function _initSessionManager()
    {
        $this->bootstrap('Di');

        $di = $this->getResource('Di');

        Session\Container::setDefaultManager(
            $di->get('Zend\Session\SessionManager')
        );
    }
}
