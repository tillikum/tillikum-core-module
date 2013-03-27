<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Billing;

use DateTime;
use Doctrine\ORM\EntityManager;

class AdHocEvent extends \Tillikum_Form
{
    protected $em;

    public $entity;

    public function bind($entity)
    {
        $this->entity = $entity;

        $this->rule_id->setValue($entity->rule ? $entity->rule->id : '');
        $this->currency->setValue($entity->currency);
        $this->amount->setValue($entity->amount);
        $this->effective->setValue($entity->effective ? $entity->effective->format('Y-m-d') : '');
        $this->description->setValue($entity->description);

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->entity)) {
            return;
        }

        $this->entity->rule = $this->em->find(
            'Tillikum\Entity\Billing\Rule\AdHoc',
            $this->rule_id->getValue()
        );
        $this->entity->currency = $this->currency->getValue();
        $this->entity->amount = $this->amount->getValue();
        $this->entity->effective = new DateTime($this->effective->getValue());
        $this->entity->description = $this->description->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $ruleId = new \Zend_Form_Element_Select(
            'rule_id',
            array(
                'label' => 'Rule',
                'required' => true,
            )
        );

        $currency = new \Zend_Form_Element_Hidden(
            'currency',
            array(
                'decorators' => array(
                    'ViewHelper',
                ),
                'required' => true,
                'value' => 'USD',
            )
        );

        $amount = new \Tillikum_Form_Element_Number(
            'amount',
            array(
                'attribs' => array(
                    'min' => '-9999.99',
                    'max' => '9999.99',
                    'step' => '0.01',
                    'title' => 'Value must be precise to no more than 2' .
                               ' decimal places'
                ),
                'label' => 'Amount',
                'required' => true,
                'validators' => array(
                    'Float',
                    new \Zend_Validate_Between(-9999.99, 9999.99)
                )
            )
        );

        $effective = new \Tillikum_Form_Element_Date(
            'effective',
            array(
                'label' => 'Effective date',
                'required' => true,
                'value' => date('Y-m-d'),
            )
        );

        $description = new \Zend_Form_Element_Textarea(
            'description',
            array(
                'attribs' => array(
                    'class' => 'short',
                ),
                'label' => 'Description',
                'required' => true,
                'filters' => array(
                    'StringTrim',
                )
            )
        );

        $this->setMethod('POST')
            ->addElements(
                array(
                    $ruleId,
                    $currency,
                    $amount,
                    $effective,
                    $description,
                    $this->createSubmitElement(array('label' => 'Create'))
                )
            );
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        $rules = $this->em->createQuery(
            "
            SELECT r.id, r.description
            FROM Tillikum\Entity\Billing\Rule\AdHoc r
            ORDER BY r.description
            "
        )
            ->getResult();

        $ruleOptions = array();
        foreach ($rules as $rule) {
            $ruleOptions[$rule['id']] = $rule['description'];
        }

        $this->rule_id->setMultiOptions($ruleOptions);

        return $this;
    }
}
