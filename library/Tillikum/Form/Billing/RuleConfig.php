<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Billing;

class RateConfig extends \Tillikum_Form_InlineSubForm
{
    public function init()
    {
        parent::init();

        $start = new \Tillikum_Form_Element_Date(
            'start',
            array(
                'label' => 'Start date',
                'required' => true
            )
        );

        $end = new \Tillikum_Form_Element_Date(
            'end',
            array(
                'label' => 'End date',
                'required' => true
            )
        );

        $code = new \Zend_Form_Element_Text(
            'code',
            array(
                'required' => true,
                'label' => 'Code',
                'filters' => array(
                    'StringTrim'
                )
            )
        );

        $amount = new \Tillikum_Form_Element_Number(
            'amount',
            array(
                'attribs' => array(
                    'min' => '-9999.99',
                    'max' => '9999.99',
                    'step' => '0.01',
                    'title' => 'Value must be precise to no more than 2'
                             . ' decimal places'
                ),
                'label' => 'Amount',
                'required' => true,
                'validators' => array(
                    'Float',
                    new \Zend_Validate_Between(-9999.99, 9999.99)
                )
            )
        );

        $description = new \Zend_Form_Element_Text(
            'description',
            array(
                'label' => 'Description',
                'filters' => array(
                    'StringTrim'
                )
            )
        );

        $rateType = new \Zend_Form_Element_Select(
            'rate_type',
            array(
                'required' => true,
                'label' => 'Rate type',
                'multiOptions' => array(
                    // XXX: should come from supported methods
                    'daily' => 'Daily',
                    'fixedrange' => 'Fixed range',
                    'monthly' => 'Monthly',
                    'nightly' => 'Nightly',
                    'weekly' => 'Weekly'
                )
            )
        );

        $prorate = new \Zend_Form_Element_Select(
            'prorate',
            array(
                'label' => 'Prorate',
                'multiOptions' => array(
                    '' => 'No',
                    'daily' => 'Daily',
                    'nightly' => 'Nightly'
                )
            )
        );

        $exportTo = new \Zend_Form_Element_Select(
            'export_to',
            array(
                'label' => 'Export to',
                'multiOptions' => array(
                    // XXX: OSU
                    '' => 'None',
                    'banner' => 'Banner',
                    'into' => 'INTO'
                )
            )
        );

        $this->addElements(array(
            $start,
            $end,
            $code,
            $amount,
            $description,
            $rateType,
            $prorate,
            $exportTo
        ))
        ->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'Description',
                array(
                    'tag' => 'p',
                    'class' => 'description'
                )
            ),
            array(
                array(
                    'DdHtmlTag' => 'HtmlTag'
                ),
                array(
                    'id' => array(
                        'callback' => function ($decorator) {
                            return $decorator->getElement()->getId() . '-element';
                        }
                    ),
                    'tag' => 'dd'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'dt'
                )
            ),
            array(
                array(
                    'DlHtmlTag' => 'HtmlTag'
                ),
                array(
                    'tag' => 'dl',
                    'class' => 'inline'
                )
            )
        ));
    }
}
