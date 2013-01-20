<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Booking;

use Doctrine\ORM\EntityManager;

class FacilityRate extends Rate
{
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        $rules = $this->em->createQuery(
            "
            SELECT r.id, r.description
            FROM Tillikum\Entity\Billing\Rule\FacilityBooking r
            ORDER BY r.description ASC
            "
        )
            ->getResult();

        $multiOptions = array('' => '');
        foreach ($rules as $rule) {
            $multiOptions[$rule['id']] = $rule['description'];
        }

        $this->rule_id->setMultiOptions($multiOptions);

        return $this;
    }
}
