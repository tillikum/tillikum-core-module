<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Plugin;

use Zend_Controller_Front as FrontController;
use Zend_Controller_Request_Abstract as AbstractRequest;
use Zend_Controller_Plugin_Abstract as AbstractPlugin;

class DummyAuthentication extends AbstractPlugin
{
    public function preDispatch(AbstractRequest $request)
    {
        if ($request->module === 'default' && $request->controller === 'error') {
            return;
        }

        $frontController = FrontController::getInstance();
        $bootstrap = $frontController->getParam('bootstrap');
        $serviceManager = $bootstrap->getResource('ServiceManager');

        $authService = $serviceManager->get(
            'Zend\Authentication\AuthenticationService'
        );

        if (!$authService->hasIdentity()) {
            $result = $authService->authenticate();

            if (!$result->isValid()) {
                throw new \Zend_Controller_Exception(
                    implode("\n", $result->getMessages()),
                    403
                );
            }
        }
    }
}
