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

class Mealplan extends \Tillikum_Form
{
    protected $em;

    public $booking;

    public function bind($booking)
    {
        $this->booking = $booking;

        $this->id->setValue($booking->id);
        $this->mealplan_id->setValue($booking->mealplan ? $booking->mealplan->id : '');
        $this->start->setValue($booking->start ? $booking->start->format('Y-m-d') : '');
        $this->end->setValue($booking->end ? $booking->end->format('Y-m-d') : '');
        $this->note->setValue($booking->note);

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->booking)) {
            return;
        }

        $this->booking->mealplan = $this->em->find(
            'Tillikum\Entity\Mealplan\Mealplan',
            $this->mealplan_id->getValue()
        );
        $this->booking->start = new DateTime($this->start->getValue());
        $this->booking->end = new DateTime($this->end->getValue());
        $this->booking->note = $this->note->getValue();

        return $this;
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

        $mealplanId = new \Zend_Form_Element_Select(
            'mealplan_id',
            array(
                'label' => 'Meal plan',
                'multiOptions' => array(),
                'required' => true,
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

        $note = new \Zend_Form_Element_Textarea(
            'note',
            array(
                'attribs' => array(
                    'class' => 'short',
                ),
                'label' => 'Notes',
            )
        );

        $this->addElements(
            array(
                $id,
                $mealplanId,
                $start,
                $end,
                $note,
                $this->createSubmitElement(array('label' => 'Next...')),
            )
        );
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        $mealplans = $this->em->createQuery(
            "
            SELECT p.id, p.name
            FROM Tillikum\Entity\Mealplan\Mealplan p
            WHERE p.is_active = :isActive
            ORDER BY p.name
            "
        )
            ->setParameter('isActive', true)
            ->getResult();

        $mealplanOptions = array('' => '');
        foreach ($mealplans as $row) {
            $mealplanOptions[$row['id']] = $row['name'];
        }

        $this->mealplan_id->setMultiOptions($mealplanOptions);

        return $this;
    }
}
