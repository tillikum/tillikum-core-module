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

class CasAuthentication extends AbstractPlugin
{
    public function preDispatch(AbstractRequest $request)
    {
        if ($request->module === 'default' && $request->controller === 'auth') {
            return;
        }

        $frontController = FrontController::getInstance();
        $bootstrap = $frontController->getParam('bootstrap');
        $serviceManager = $bootstrap->getResource('ServiceManager');

        $authService = $serviceManager->get(
            'Zend\Authentication\AuthenticationService'
        );

        if (!$authService->hasIdentity()) {
            $response = $this->getResponse();

            $currentUri = sprintf(
                '%s://%s%s%s',
                $request->getScheme(),
                $request->getHttpHost(),
                $request->getBaseUrl(),
                $request->getPathInfo()
            );

            $adapter = $authService->getAdapter();
            $adapter->setLoginParameters(
                array(
                    'service' => $currentUri,
                )
            );

            // Assume user is back here from a CAS authentication
            if ($request->getQuery('ticket')) {
                $adapter->setServiceValidateParameters(
                    array(
                        'service' => $currentUri,
                        'ticket' => $request->getQuery('ticket'),
                    )
                );

                // Validate the ticket
                $result = $authService->authenticate();

                if (!$result->isValid()) {
                    $response->setRedirect(
                        $adapter->createLoginUri()
                    );
                }
            // Assume the user just got here
            } else {
                $response->setRedirect(
                    $adapter->createLoginUri()
                );
            }
        }
    }
}
