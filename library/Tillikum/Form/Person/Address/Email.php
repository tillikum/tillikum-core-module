<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Person\Address;

use Doctrine\ORM\EntityManager;

class Email extends \Tillikum_Form
{
    protected $em;
    protected $entity;

    public function bind($entity)
    {
        $this->entity = $entity;

        $this->type_id->setValue($entity->type ? $entity->type->id : null);
        $this->value->setValue($entity->value);
        $this->is_primary->setValue($entity->is_primary);

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->entity)) {
            return;
        }

        $this->entity->type = $this->em->find(
            'Tillikum\Entity\Person\Address\Type',
            $this->type_id->getValue()
        );
        $this->entity->value = $this->value->getValue();
        $this->entity->is_primary = (bool) $this->is_primary->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $typeId = new \Zend_Form_Element_Select(
            'type_id',
            array(
                'label' => 'Email type',
                'multiOptions' => array(),
                'required' => true,
            )
        );

        $value = new \Zend_Form_Element_Text(
            'value',
            array(
                'filters' => array(
                    'StringTrim',
                ),
                'label' => 'Email address',
                'required' => true,
                'validators' => array(
                    'EmailAddress',
                ),
            )
        );

        $isPrimary = new \Zend_Form_Element_Checkbox(
            'is_primary',
            array(
                'label' => 'Is this the person’s primary email address?',
            )
        );

        $this->addElements(
            array(
                $typeId,
                $value,
                $isPrimary,
            )
        );
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        $typeResult = $this->em
            ->createQuery(
                '
                SELECT t.id, t.name
                FROM Tillikum\Entity\Person\Address\Type t
                ORDER BY t.name
                '
            )
            ->getResult();

        $typeMultiOptions = array('' => '');
        foreach ($typeResult as $row) {
            $typeMultiOptions[$row['id']] = $row['name'];
        }

        $this->getElement('type_id')
            ->setMultiOptions($typeMultiOptions);

        return $this;
    }
}
