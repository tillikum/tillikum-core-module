<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Permissions\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;

class Acl extends ZendAcl
{
    public function __construct()
    {
        $this->addResource('billing_navigation');
        $this->addResource('booking_navigation');
        $this->addResource('facility_navigation');
        $this->addResource('job_navigation');
        $this->addResource('person_navigation');
        $this->addResource('report_navigation');

        $this->addResource('billing');
        $this->addResource('billing_event');
        $this->addResource('billing_invoice');
        $this->addResource('billing_rule');
        $this->addResource('contract');
        $this->addResource('contract_signature');
        $this->addResource('facility');
        $this->addResource('facility_booking');
        $this->addResource('facility_config');
        $this->addResource('facility_hold');
        $this->addResource('facility_group');
        $this->addResource('facility_group_config');
        $this->addResource('job');
        $this->addResource('mealplan_booking');
        $this->addResource('person');
        $this->addResource('report');

        $this->addRole('_user');
    }
}
