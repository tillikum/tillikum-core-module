<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Plugin;

use Zend_Auth;
use Zend_Controller_Front as FrontController;
use Zend_Controller_Request_Abstract as AbstractRequest;
use Zend_Controller_Plugin_Abstract as AbstractPlugin;

class BuiltinAuthentication extends AbstractPlugin
{
    public function preDispatch(AbstractRequest $request)
    {
        if ($request->module === 'default' && $request->controller === 'auth') {
            return;
        }

        $frontController = FrontController::getInstance();
        $bootstrap = $frontController->getParam('bootstrap');
        $serviceManager = $bootstrap->getResource('ServiceManager');

        $authService = $serviceManager->get('Di')
            ->get('Zend\Authentication\AuthenticationService');

        if (!$authService->hasIdentity()) {
            $request->setModuleName('default');
            $request->setControllerName('auth');
            $request->setActionName('login');
        }
    }
}
