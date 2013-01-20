<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Facility;

class Availability extends \Tillikum_Form
{
    public function init()
    {
        parent::init();

        $date = new \Tillikum_Form_Element_Date(
            'date',
            array(
                'label' => 'Facility configuration date',
                'value' => date('Y-m-d'),
                'required' => true
            )
        );

        $facilityGroupId = new \Zend_Form_Element_Hidden(
            'facilitygroup_id',
            array(
                'decorators' => array(
                    'ViewHelper'
                )
            )
        );

        $facilityGroupName = new \Tillikum_Form_Element_Search(
            'facilitygroup_name',
            array(
                'ignore' => true,
                'label' => 'Facility group name'
            )
        );

        $q = $this->em->createQuery(
            'SELECT t.id, t.name FROM Tillikum\Entity\Facility\Config\Room\Type t'
          . ' WHERE t.is_active = true'
          . ' ORDER BY t.name'
        );

        $types = array();
        foreach ($q->getResult() as $row) {
            $types[$row['id']] = $row['name'];
        }

        $type = new \Zend_Form_Element_Select(
            'type',
            array(
                'label' => 'Type',
                'multiOptions' => $types
            )
        );

        $gender = new \Zend_Form_Element_Text(
            'gender',
            array(
                'label' => 'Gender'
            )
        );

        $availableSpace = new \Tillikum_Form_Element_Number(
            'available_space',
            array(
                'label' => 'Spaces available (at minimum)',
                'min' => 0,
                'validators' => array(
                    new \Zend_Validate_GreaterThan(0)
                )
            )
        );

        $this->addElements(array(
            $date,
            $facilityGroupId,
            $facilityGroupName,
            $type,
            $gender,
            $availableSpace,
            $this->createSubmitElement(array('label' => 'Search'))
        ));
    }
}
