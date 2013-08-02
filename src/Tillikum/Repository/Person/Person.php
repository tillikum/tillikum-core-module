<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Repository\Person;

use Doctrine\ORM\EntityRepository;

class Person extends EntityRepository
{
    public function getAutocompleteQuery($input)
    {
        $input = trim($input);
        $input = preg_replace('/\s{2,}/', ' ', $input);
        $input = preg_replace('/,\s?/', ' ', $input);

        $qb = $this->getEntityManager()
            ->createQueryBuilder();

        $qb->select('p')
            ->from('Tillikum\Entity\Person\Person', 'p')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->like(
                        $qb->expr()->concat(
                            'COALESCE(p.given_name, \'\')', $qb->expr()->concat(
                                $qb->expr()->literal(' '), 'COALESCE(p.family_name, \'\')'
                            )
                        ),
                        ':input'
                    ),
                    $qb->expr()->like(
                        $qb->expr()->concat(
                            'COALESCE(p.family_name, \'\')', $qb->expr()->concat(
                                $qb->expr()->literal(' '), 'COALESCE(p.given_name, \'\')'
                            )
                        ),
                        ':input'
                    )
                )
            )
            ->setParameter('input', $input . '%');

        return $qb->getQuery();
    }
}
