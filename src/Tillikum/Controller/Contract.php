<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

use Zend_Controller_Exception as ControllerException;

class Tillikum_Controller_Contract extends Tillikum_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->getAcl()->isAllowed('_user', 'contract', 'read')) {
            throw new ControllerException(
                'You do not have access to the contract module.',
                403
            );
        }
    }
}
