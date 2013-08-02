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

class Room extends \Tillikum_Form implements EntityManagerAwareInterface
{
    protected $em;

    public $facility;

    public function bind($facility)
    {
        $this->facility = $facility;

        $this->id->setValue($facility->id);
        $this->facility_group->setValue($facility->facility_group ? $facility->facility_group->id : '');

        return $this;
    }

    public function bindValues()
    {
        if (!isset($this->facility)) {
            return;
        }

        if ($this->facility_group->getValue()) {
            $this->facility->facility_group = $this->em->find(
                'Tillikum\Entity\FacilityGroup\FacilityGroup',
                $this->facility_group->getValue()
            );
        }

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

        $facilityGroup = new \Zend_Form_Element_Select(
            'facility_group',
            array(
                'description' => 'Building names listed here are from the latest' .
                                 ' building configuration.',
                'label' => 'Which building is this facility a member of?',
                'multiOptions' => array(),
            )
        );

        $this->addElements(
            array(
                $id,
                $facilityGroup,
                $this->createSubmitElement(
                    array(
                        'label' => 'Save',
                    )
                )
            )
        );
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        $buildings = $this->em->createQuery(
            "
            SELECT b
            FROM Tillikum\Entity\FacilityGroup\Building\Building b
            JOIN b.configs c
            ORDER BY c.name
            "
        )
            ->getResult();

        $buildingOptions = array();
        foreach ($buildings as $building) {
            $buildingOptions[$building->id] = $building->configs->last()->name;
        }

        $this->facility_group->setMultiOptions($buildingOptions);

        return $this;
    }
}
