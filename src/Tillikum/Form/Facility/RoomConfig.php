<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Facility;

use Doctrine\ORM\EntityManager;
use Tillikum\ORM\EntityManagerAwareInterface;

class RoomConfig extends Config implements EntityManagerAwareInterface
{
    public function bind($entity)
    {
        parent::bind($entity);

        $this->floor->setValue($entity->floor);
        $this->section->setValue($entity->section);
        $this->suite->setValue($entity->suite ? $entity->suite->id : '');
        $this->type->setValue($entity->type ? $entity->type->id : null);

        return $this;
    }

    public function bindValues()
    {
        parent::bindValues();

        if (!isset($this->entity)) {
            return;
        }

        $this->entity->floor = $this->floor->getValue();
        $this->entity->section = $this->section->getValue();
        $this->entity->suite = $this->em->find('Tillikum\Entity\Facility\Config\Room\Suite', $this->suite->getValue());
        $this->entity->type = $this->em->find('Tillikum\Entity\Facility\Config\Room\Type', $this->type->getValue());

        return $this;
    }

    public function init()
    {
        parent::init();

        $this->removeElement('tillikum_submit');

        $type = new \Zend_Form_Element_Select(
            'type',
            array(
                'label' => 'Type',
                'required' => true,
            )
        );

        $floor = new \Zend_Form_Element_Text(
            'floor',
            array(
                'label' => 'Floor',
            )
        );

        $section = new \Zend_Form_Element_Text(
            'section',
            array(
                'label' => 'Section',
            )
        );

        $suite = new \Zend_Form_Element_Select(
            'suite',
            array(
                'label' => 'Suite',
            )
        );

        $this->addElements(
            array(
                $type,
                $floor,
                $section,
                $suite,
                $this->createSubmitElement(
                    array(
                        'label' => 'Save'
                    )
                )
            )
        );
    }

    public function setEntityManager(EntityManager $em)
    {
        parent::setEntityManager($em);

        $q = $this->em->createQuery(
            "
            SELECT t.id, t.name
            FROM Tillikum\Entity\Facility\Config\Room\Type t
            WHERE t.is_active = true
            ORDER BY t.name
            "
        );

        $types = array();
        foreach ($q->getResult() as $row) {
            $types[$row['id']] = $row['name'];
        }

        $this->type->setMultiOptions($types);

        $q = $this->em->createQuery(
            "
            SELECT s.id, s.name
            FROM Tillikum\Entity\Facility\Config\Room\Suite s
            ORDER BY s.name
            "
        );

        $suites = array('' => '');
        foreach ($q->getResult() as $row) {
            $suites[$row['id']] = $row['name'];
        }

        $this->suite->setMultiOptions($suites);

        return $this;
    }
}
