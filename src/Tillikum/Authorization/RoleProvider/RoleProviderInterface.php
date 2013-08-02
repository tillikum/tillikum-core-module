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

interface RoleProviderInterface
{
    /**
     * @param string $identity Identity of the user
     */
    public function __construct($identity);

    /**
     * Configure an Acl object
     *
     * This is how Tillikum understands your internal mapping of users to
     * roles. This could call custom code to fetch roles from a separate
     * database, or simply make everyone an admin.
     *
     * @param  Acl ACL object to configure
     * @return void
     */
    public function configureAcl(Acl $acl);
}
