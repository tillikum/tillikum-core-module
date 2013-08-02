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

class FormDatetime extends AbstractValidate
{
    /**
     * Message template for an invalid date and time
     */
    const INVALID = 'dateInvalid';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID => "'%value%' must be a valid date and time. Example format: 2010-06-15T12:34:56+00:00"
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Determines if a datetime input is valid according to the HTML5
     * date input type specification.
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_setValue((string) $value);

        if (date_create_from_format('Y-m-d\TH:i:sP', $value) === false) {
            $this->_error(self::INVALID);

            return false;
        }

        return true;
    }
}
