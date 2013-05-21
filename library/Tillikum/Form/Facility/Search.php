<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Facility;

use DateTime;
use Doctrine\ORM\EntityManager;
use Tillikum\ORM\EntityManagerAwareInterface;

class Search extends \Tillikum_Form implements EntityManagerAwareInterface
{
    protected $entityManager;

    public function init()
    {
        parent::init();

        $date = new \Tillikum_Form_Element_Date(
            'date',
            array(
                'label' => 'Facility configuration date',
                'required' => true,
            )
        );

        $facilityGroupIds = new \Zend_Form_Element_Multiselect(
            'facilitygroup_ids',
            array(
                'label' => 'Facility group',
                'multiOptions' => array(),
            )
        );

        $gender = new \Zend_Form_Element_Text(
            'gender',
            array(
                'label' => 'Gender',
            )
        );

        $capacity = new \Tillikum_Form_Element_Number(
            'capacity',
            array(
                'label' => 'Capacity',
                'validators' => array(
                    new \Zend_Validate_GreaterThan(0),
                ),
            )
        );

        $availableSpace = new \Tillikum_Form_Element_Number(
            'available_space',
            array(
                'label' => 'Spaces available (at minimum)',
                'validators' => array(
                    new \Zend_Validate_GreaterThan(0),
                ),
            )
        );

        $tags = new \Zend_Form_Element_Multiselect(
            'tags',
            array(
                'label' => 'Tags',
                'multiOptions' => array(),
            )
        );

        $this->addElements(
            array(
                $date,
                $facilityGroupIds,
                $gender,
                $capacity,
                $availableSpace,
                $tags,
                $this->createSubmitElement(array('label' => 'Search')),
            )
        );
    }

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        $rows = $this->entityManager->createQuery(
            '
            SELECT fg.id, fgc.name
            FROM Tillikum\Entity\FacilityGroup\FacilityGroup fg
            JOIN fg.configs fgc
            WHERE :now BETWEEN fgc.start AND fgc.end
            ORDER BY fgc.name
            '
        )
            ->setParameter('now', new DateTime())
            ->getResult();

        $multiOptions = array();
        foreach ($rows as $row) {
            $multiOptions[$row['id']] = $row['name'];
        }

        $this->facilitygroup_ids->setMultiOptions($multiOptions);
        $this->facilitygroup_ids->setAttrib('size', min(10, count($multiOptions)));

        $rows = $this->entityManager->createQuery(
            '
            SELECT t.id, t.name
            FROM Tillikum\Entity\Facility\Config\Tag t
            WHERE t.is_active = :isActive
            ORDER BY t.name
            '
        )
            ->setParameter('isActive', true)
            ->getResult();

        $multiOptions = array();
        foreach ($rows as $row) {
            $multiOptions[$row['id']] = $row['name'];
        }

        $this->tags->setMultiOptions($multiOptions);
        $this->tags->setAttrib('size', min(10, count($multiOptions)));
    }
}
