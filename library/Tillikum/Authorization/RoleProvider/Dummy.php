<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Authorization\RoleProvider;

use Zend\Permissions\Acl\Acl;

class Dummy implements RoleProviderInterface
{
    protected $identity;

    public function __construct($identity)
    {
        $this->identity = $identity;
    }

    /**
     * Allow the _user meta-role access to everything
     */
    public function configureAcl(Acl $acl)
    {
        $acl->allow('_user');
    }
}
