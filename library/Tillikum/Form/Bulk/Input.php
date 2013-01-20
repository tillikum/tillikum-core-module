<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Bulk;

use Tillikum_Form as Form;

class Input extends Form
{
    /**
     * Hold the raw input between {isValid()} and {getValues()}
     *
     * @var array
     */
    protected $rawArray;

    public function init()
    {
        parent::init();

        $text = new \Zend_Form_Element_Textarea(
            'text',
            array(
                'description' => 'You can copy from most spreadsheet software'
                               . ' and paste it into this box. We will convert'
                               . ' it into a table for use in Tillikum on the'
                               . ' next screen.',
                'label' => 'Text input'
            )
        );

        $file = new \Zend_Form_Element_File(
            'file',
            array(
                'description' => 'You may also upload a “Comma-Separated'
                               . ' Values” file here. We will convert it'
                               . ' into a table for use in Tillikum on'
                               . ' the next screen.',
                'label' => 'File upload'
            )
        );

        $hasHeader = new \Zend_Form_Element_Checkbox(
            'has_header',
            array(
                'description' => 'If the first row of your data contains titles'
                               . ' (headers) for your data, check this box and'
                               . ' we will use them to assist us with the'
                               . ' data conversion.',
                'label' => 'First row contains column titles'
            )
        );

        $this->addElements(array(
            $text,
            $file,
            $hasHeader,
            $this->createSubmitElement(array('label' => 'Next…'))
        ));
    }

    public function isValid($data)
    {
        if (!parent::isValid($data)) {
            return false;
        }

        $filename = $this->file->getFileName();

        if (empty($filename) && empty($data['text'])) {
            $this->text->addError(sprintf(
                $this->getTranslator()->translate(
                    'You must either copy-and-paste from a spreadsheet into'
                  . ' this box or upload a file below.'
                )
            ));
            $this->file->addError(sprintf(
                $this->getTranslator()->translate(
                    'You must either upload a file here or copy-and-paste from'
                  . ' a spreadsheet into the box above.'
                )
            ));

            return false;
        }

        if (!empty($filename) && !empty($data['text'])) {
            $this->text->addError(sprintf(
                $this->getTranslator()->translate(
                    'You must either copy-and-paste from a spreadsheet into'
                  . ' this box or upload a file below, but not both.'
                )
            ));
            $this->file->addError(sprintf(
                $this->getTranslator()->translate(
                    'You must either upload a file here or copy-and-paste from'
                  . ' a spreadsheet into the box above, but not both.'
                )
            ));

            return false;
        }

        if (!empty($data['text'])) {
            // As far as I can tell there can't really be any errors here.
            $this->rawArray = array();
            foreach (explode("\r\n", trim($data['text'])) as $row) {
                $this->rawArray[] = str_getcsv($row, "\t");
            }
        } elseif (!empty($filename)) {
            if (!$this->file->receive()) {
                $this->file->addError(sprintf(
                    $this->getTranslator()->translate(
                        'There was a problem receiving the file you uploaded. If'
                      . ' you try again and the error persists, please contact'
                      . ' your support staff.'
                  )
                ));

                return false;
            }

            $fp = fopen($filename, 'rb');
            if ($fp === false) {
                $this->file->addError(sprintf(
                    $this->getTranslator()->translate(
                        'There was a problem reading the file you uploaded. If'
                      . ' you try again and the error persists, please contact'
                      . ' your support staff.'
                  )
                ));

                return false;
            }

            $this->rawArray = array();
            while ($row = fgetcsv($fp)) {
                $this->rawArray[] = $row;
            }
        }

        return true;
    }

    public function getValues($suppressArrayNotation = false)
    {
        $values = parent::getValues($suppressArrayNotation);

        // Return a data structure with headers and data parsed for use
        $parsed = array(
            'header' => array(),
            'data' => array()
        );

        $createHeader = function ($rows) {
            $header = array();
            $c = 'A';
            for ($i = 0; $i < count($rows[0]); $i++) {
                $header[] = $c;
                // Make use of PHP's string incrementing behavior to make
                // spreadsheet-like column headers
                //
                // http://php.net/manual/en/language.operators.increment.php
                $c++;
            }

            return $header;
        };

        if ((bool) $values['has_header']) {
            $parsed['header'] = array_slice($this->rawArray, 0, 1);
            $parsed['header'] = $parsed['header'][0];
            $parsed['data'] = array_slice($this->rawArray, 1);
        } else {
            $parsed['header'] = $createHeader($this->rawArray);
            $parsed['data'] = $this->rawArray;
        }

        $values['parsed'] = $parsed;

        return $values;
    }
}
