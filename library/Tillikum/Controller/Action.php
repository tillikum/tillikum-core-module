<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

use Tillikum\Listener\Audit as AuditListener;
use Zend\Session;

abstract class Tillikum_Controller_Action extends Zend_Controller_Action
{
    public function getAcl()
    {
        $serviceManager = $this->getServiceManager();

        return $serviceManager->get('Acl');
    }

    public function getAuthenticationService()
    {
        $serviceManager = $this->getServiceManager();

        return $serviceManager->get('Zend\Authentication\AuthenticationService');
    }

    public function getDi()
    {
        $serviceManager = $this->getServiceManager();

        return $serviceManager->get('Di');
    }

    public function getEntityManager()
    {
        $serviceManager = $this->getServiceManager();

        return $serviceManager->get('EntityManager');
    }

    public function getLog()
    {
        $serviceManager = $this->getServiceManager();

        return $serviceManager->get('Logger');
    }

    public function getServiceManager()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');

        return $bootstrap->getResource('ServiceManager');
    }

    public function getSessionContainer($name = 'Default')
    {
        return $this->getDi()->get(
            'Zend\Session\Container',
            array(
                'name' => $name,
            )
        );
    }

    public function getTranslator()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');

        return $bootstrap->getResource('Translate');
    }

    public function init()
    {
        $eventManager = $this->getEntityManager()
            ->getEventManager();

        $identity = $this->getAuthenticationService()
            ->getIdentity();

        $eventManager->addEventSubscriber(
            new AuditListener($identity)
        );

        $paths = $this->view->getScriptPaths();
        $this->view->setScriptPath(null);
        foreach ($paths as $path) {
            $this->view->addScriptPath(
                sprintf(
                    $path,
                    $this->_request->module
                )
            );
        }
    }

    public function preDispatch()
    {
        $this->_response->setHeader(
            'Content-Type',
            "text/html;charset={$this->view->getEncoding()}"
        );
    }

    public function postDispatch()
    {
        if ($this->getAuthenticationService()->hasIdentity()) {
            $mainNavigation = $this->getServiceManager()
                ->get('LoggedInMainNavigation');
        } else {
            $mainNavigation = $this->getServiceManager()
                ->get('LoggedOutMainNavigation');
        }

        $pagesWithCurrentModule = $mainNavigation->findAllByModule(
            $this->_request->module
        );

        foreach ($pagesWithCurrentModule as $page) {
            $page->setActive(true);
        }

        $this->_helper->layout()->sideNavigation = $mainNavigation;

        $siteLogoPage = $this->getServiceManager()
            ->get('SiteLogo');

        $this->_helper->layout()->siteLogoNavigation = $siteLogoPage;

        $availableTranslations = $this->getTranslator()
            ->getList();
        $layoutTranslations = array();
        if (count((array) $availableTranslations) > 1) {
            ksort($availableTranslations);

            $currentTranslationLocale = (string) $this->getTranslator()
                ->getLocale();

            foreach (array_keys($availableTranslations) as $locale) {
                $translation = array(
                    'text' => Locale::getDisplayLanguage($locale, $locale),
                    'selected' => $locale === $currentTranslationLocale ? true : false
                );

                $layoutTranslations[] = $translation;
            }
        }

        $this->_helper->layout()->translations = $layoutTranslations;
    }
}
