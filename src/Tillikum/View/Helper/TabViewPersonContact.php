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
 * Helper for rendering the person tab view 'contact' section
 */
class TabViewPersonContact extends AbstractHelper
{
    protected $script;

    public function __construct()
    {
        $this->script = '_partials/contact.phtml';
    }

    public function tabViewPersonContact()
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
            $this->script,
            array(
                'person' => $person,
            )
        );
    }
}
