<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Contract;

use DateTime;
use Doctrine\ORM\EntityManager;

class Signature extends \Tillikum_Form
{
    public $entity;

    protected $em;

    public function bind($entity)
    {
        $this->entity = $entity;

        $this->person_id->setValue($entity->person ? $entity->person->id : '');
        $this->contract_id->setValue($entity->contract ? $entity->contract->id : '');
        $this->signed_at->setValue($entity->signed_at ? $entity->signed_at->format('Y-m-d') : '');
        $this->is_cosigned->setValue($entity->is_cosigned);
        $this->is_cancelled->setValue($entity->is_cancelled);

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->entity)) {
            return;
        }

        $this->entity->person = $this->em->find(
            'Tillikum\Entity\Person\Person',
            $this->person_id->getValue()
        );
        $this->entity->contract = $this->em->find(
            'Tillikum\Entity\Contract\Contract',
            $this->contract_id->getValue()
        );
        $this->entity->signed_at = strlen($this->signed_at->getValue()) > 0
            ? new DateTime($this->signed_at->getValue())
            : new DateTime();

        $this->entity->is_cosigned = (bool) $this->is_cosigned->getValue();
        $this->entity->is_cancelled = (bool) $this->is_cancelled->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $personId = new \Zend_Form_Element_Hidden(
            'person_id',
            array(
                'decorators' => array(
                    'ViewHelper',
                ),
                'required' => true,
            )
        );

        $contractId = new \Zend_Form_Element_Select(
            'contract_id',
            array(
                'label' => 'Contract',
                'required' => true,
            )
        );

        $signedAt = new \Tillikum_Form_Element_Date(
            'signed_at',
            array(
                'description' => 'Determines age at time of signing. Leave blank for today’s date.',
                'label' => 'When was the contract originally signed?',
            )
        );

        $isCosigned = new \Zend_Form_Element_Checkbox(
            'is_cosigned',
            array(
                'label' => 'Is the contract cosigned?',
            )
        );

        $isCancelled = new \Zend_Form_Element_Checkbox(
            'is_cancelled',
            array(
                'label' => 'Is the contract cancelled?',
            )
        );

        $this->addElements(
            array(
                $personId,
                $contractId,
                $signedAt,
                $isCosigned,
                $isCancelled,
                $this->createSubmitElement(
                    array(
                        'label' => 'Sign'
                    )
                )
            )
        );
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        $contracts = $this->em->createQuery(
            "
            SELECT c.id, c.name
            FROM Tillikum\Entity\Contract\Contract c
            ORDER BY c.name
            "
        )
            ->getResult();

        $contractIdMultiOptions = array('' => '');
        foreach ($contracts as $contract) {
            $contractIdMultiOptions[$contract['id']] = $contract['name'];
        }

        $this->contract_id->setMultiOptions($contractIdMultiOptions);

        return $this;
    }
}
