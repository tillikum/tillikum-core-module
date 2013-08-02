<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Validate;

use Zend_Validate_Abstract as AbstractValidate;

class FormDate extends AbstractValidate
{
    /**
     * Message template for an invalid date
     */
    const INVALID = 'dateInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID => "'%value%' must be a valid date. Example format: 2010-06-15"
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Determines if an date input is valid according to the HTML5
     * date input type specification.
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_setValue((string) $value);

        $test = date_create_from_format('Y-m-d', $value);

        if ($test === false) {
            $this->_error(self::INVALID);

            return false;
        }

        if ($test->format('Y-m-d') !== $value) {
            $this->_error(self::INVALID);

            return false;
        }

        return true;
    }
}
