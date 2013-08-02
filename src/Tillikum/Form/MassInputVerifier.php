<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

class Tillikum_Form_MassInputVerifier extends Tillikum_Form
{
    protected $requiredElements;

    public function __construct()
    {
        parent::__construct();

        $this->requiredElements = array();
    }

    public function init()
    {
        parent::init();

        $mapSubform = new Zend_Form_SubForm(
            array(
                'decorators' => array(
                    array(
                        array(
                            'Map' => 'ViewScript'
                        ),
                        array(
                            'viewScript' => 'partials/mass_map.phtml'
                        )
                    ),
                    array(
                        'HtmlTag',
                        array(
                            'tag' => 'table'
                        )
                    ),
                    array(
                        array(
                            'MapErrors' => 'ViewScript'
                        ),
                        array(
                            'viewScript' => 'partials/mass_map_errors.phtml'
                        )
                    )
                ),
                'order' => 0
            )
        );

        $submit = new Tillikum_Form_Element_Submit(
            'submit',
            array(
                'label' => 'Next…',
                'order' => 1
            )
        );

        $this->addSubForm($mapSubform, 'map');
        $this->addElements(
            array(
                $submit
            )
        );

        $this->setMethod('POST')
        ->setDecorators(
            array(
                'FormElements',
                'Form'
            )
        );
    }

    public function addRow($id, $label, $options)
    {
        $mapSubform = $this->getSubForm('map');
        $element = new Zend_Form_Element_Select(
            $id,
            array(
                'decorators' => array(
                    'ViewHelper'
                ),
                'label' => $label,
                'multiOptions' => array_merge(
                    array(
                        '' => '—Ignore—'
                    ),
                    $options
                )
            )
        );

        $mapSubform->addElement($element);

        return $this;
    }

    public function setRequiredElements(array $elements)
    {
        $this->requiredElements = $elements;

        return $this;
    }

    public function checkMappedData($map, $rows)
    {
        return array('result' => true);
    }

    public function isValid($data)
    {
        $isValid = parent::isValid($data);

        if (!$isValid) {
            return $isValid;
        }

        foreach ($this->requiredElements as $element) {
            if (!in_array($element, $data['map'])) {
                $this->getSubForm('map')->addError("Missing required element ($element)");
                $isValid = false;
            }
        }

        return $isValid;
    }
}
