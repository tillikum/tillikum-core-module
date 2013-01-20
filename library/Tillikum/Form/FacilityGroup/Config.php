<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\FacilityGroup;

use DateTime;
use Doctrine\ORM\EntityManager;

class Config extends \Tillikum_Form
{
    protected $em;

    public $entity;

    public function bind($entity)
    {
        $this->entity = $entity;

        $this->id->setValue($entity->id);
        $this->facilitygroup_id->setValue($entity->facility_group->id);
        $this->start->setValue($entity->start ? $entity->start->format('Y-m-d') : '');
        $this->end->setValue($entity->end ? $entity->end->format('Y-m-d') : '');
        $this->name->setValue($entity->name);
        $this->gender->setValue($entity->gender);
    }

    public function bindValues()
    {
        if (!isset($this->entity)) {
            return;
        }

        $this->entity->start = new DateTime($this->start->getValue());
        $this->entity->end = new DateTime($this->end->getValue());
        $this->entity->name = $this->name->getValue();
        $this->entity->gender = $this->gender->getValue();
    }

    public function init()
    {
        parent::init();

        $id = new \Zend_Form_Element_Hidden(
            'id',
            array(
                'decorators' => array(
                    'ViewHelper',
                )
            )
        );

        $facilityGroupId = new \Zend_Form_Element_Hidden(
            'facilitygroup_id',
            array(
                'decorators' => array(
                    'ViewHelper',
                )
            )
        );

        $start = new \Tillikum_Form_Element_Date(
            'start',
            array(
                'label' => 'Start date',
                'required' => true,
            )
        );

        $end = new \Tillikum_Form_Element_Date(
            'end',
            array(
                'label' => 'End date',
                'required' => true,
            )
        );

        $name = new \Zend_Form_Element_Text(
            'name',
            array(
                'label' => 'Name',
                'required' => true,
                'filters' => array(
                    'StringTrim',
                )
            )
        );

        $gender = new \Zend_Form_Element_Select(
            'gender',
            array(
                'label' => 'Gender',
                'required' => true,
                'multiOptions' => array(
                    'F' => 'Female',
                    'M' => 'Male',
                    'U' => 'Ungendered',
                ),
                'value' => 'U',
            )
        );

        $this->addElements(
            array(
                $id,
                $facilityGroupId,
                $start,
                $end,
                $name,
                $gender,
                $this->createSubmitElement(
                    array(
                        'label' => 'Save',
                    )
                )
            )
        );
    }

    public function isValid($data)
    {
        if (!parent::isValid($data)) {
            return false;
        }

        $startDate = new DateTime($data['start']);
        $endDate = new DateTime($data['end']);

        if ($startDate > $endDate) {
            $this->start->addError($this->getTranslator()->translate(
                'The start date must be on or before the end date.'
            ));

            $this->end->addError($this->getTranslator()->translate(
                'The end date must be on or after the start date.'
            ));

            return false;
        }

        $facilityGroup = $this->em->find(
            'Tillikum\Entity\FacilityGroup\FacilityGroup',
            $data['facilitygroup_id']
        );

        $qb = $this->em->createQueryBuilder()
            ->select('c.start, c.end')
            ->from('Tillikum\Entity\FacilityGroup\Config\Config', 'c')
            ->where('c.start <= :proposedEnd')
            ->andWhere('c.end >= :proposedStart')
            ->andWhere('c.facility_group = :facilityGroup')
            ->setParameter('facilityGroup', $facilityGroup)
            ->setParameter('proposedStart', $startDate)
            ->setParameter('proposedEnd', $endDate);

        if ($this->entity && isset($this->entity->id)) {
            $qb->andWhere('c != :entity')
                ->setParameter('entity', $this->entity);
        }

        if (count($rows = $qb->getQuery()->getResult()) > 0) {
            foreach ($rows as $row) {
                $errorMessage = sprintf(
                    $this->getTranslator()->translate(
                        'An existing configuration from %s to %s overlaps your intended configuration.'
                    ),
                    $row['start']->format('Y-m-d'),
                    $row['end']->format('Y-m-d')
                );

                $this->start->addError($errorMessage);
                $this->end->addError($errorMessage);
            }

            return false;
        }

        return true;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        return $this;
    }
}
