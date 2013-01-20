<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Report;

class Report extends \Tillikum_Form
{
    public function init()
    {
        parent::init();

        $format = new \Zend_Form_Element_Select(
            'format',
            array(
                'label' => 'In which format do you want to see the report data?',
                'multiOptions' => array(
                    'csv' => 'CSV (download a file, open with spreadsheet software)',
                    'html' => 'HTML (view report in a table in your browser)',
                ),
                'required' => true,
            )
        );

        $this->setMethod('GET')
        ->addElements(
            array(
                $format,
                $this->createSubmitElement(
                    array(
                        'label' => 'Generate report',
                        'order' => PHP_INT_MAX
                    )
                )
            )
        );
    }
}
