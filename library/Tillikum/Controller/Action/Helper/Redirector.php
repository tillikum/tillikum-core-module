<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Action\Helper;

/**
 * Exists solely to override the default behavior of its parent
 *
 * During the ZF1-ZF2 transition, it is necessary to allow ZF2 sessions to be
 * managed without interference from code in this ZF1 action helper.
 */
class Redirector extends \Zend_Controller_Action_Helper_Redirector
{
    protected $_closeSessionOnExit = false;
}
