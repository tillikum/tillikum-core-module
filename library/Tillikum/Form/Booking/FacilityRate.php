<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Booking;

use DateTime;
use Doctrine\ORM\EntityManager;

class FacilityRate extends Rate
{
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        $rules = $this->em->createQuery(
            "
            SELECT r.id,
                   r.description,
                   (
                       SELECT COUNT(c)
                       FROM Tillikum\Entity\Billing\Rule\Config\FacilityBooking c
                       JOIN c.rule rInner
                       WHERE r = rInner AND c.end >= :currentDate
                   ) AS hasCurrentConfig
            FROM Tillikum\Entity\Billing\Rule\FacilityBooking r
            ORDER BY r.description ASC
            "
        )
            ->setParameter('currentDate', new DateTime())
            ->getResult();

        $multiOptions = array('Active (as of today)' => array(), 'Archived' => array(),);
        foreach ($rules as $rule) {
            if ($rule['hasCurrentConfig'] > 0) {
                $multiOptions['Active (as of today)'][$rule['id']] = $rule['description'];
            } else {
                $multiOptions['Archived'][$rule['id']] = $rule['description'];
            }
        }

        $this->rule_id->setMultiOptions($multiOptions);

        return $this;
    }
}
