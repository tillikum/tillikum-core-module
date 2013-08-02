<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Plugin;

use Locale;
use Zend_Controller_Plugin_Abstract as AbstractPlugin;
use Zend_Controller_Request_Abstract as AbstractRequest;

class LocaleFromRequest extends AbstractPlugin
{
    public function preDispatch(AbstractRequest $request)
    {
        $acceptLanguage = $request->getServer('HTTP_ACCEPT_LANGUAGE');

        if ($acceptLanguage) {
            Locale::setDefault(
                Locale::acceptFromHttp($acceptLanguage)
            );
        }
    }
}
