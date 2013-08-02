<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\View\Helper;

use Zend_View_Helper_Abstract as AbstractHelper;

/**
 * Helper for rendering the person tab view 'billing' section
 */
class TabViewPersonBilling extends AbstractHelper
{
    protected $script;

    public function tabViewPersonBilling()
    {
        return $this;
    }

    public function canShowTab($person)
    {
        return true;
    }

    public function render($person)
    {
        return $this->view->partial(
            '_partials/billing.phtml',
            'person',
            array(
                'person' => $person,
            )
        );
    }
}
