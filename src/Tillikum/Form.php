<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

class Tillikum_Form extends \Zend_Form
{
    /**
     * @var array[]
     */
    protected static $defaultOptions = array();

    /**
     * @var string[]
     */
    protected $warnings;

    public static function clearDefaultOptions()
    {
        self::$defaultOptions = array();
    }

    public static function getDefaultOptions()
    {
        return self::$defaultOptions;
    }

    public static function setDefaultOptions(array $options)
    {
        self::$defaultOptions = $options;
    }

    public function init()
    {
        $this->setOptions(self::$defaultOptions);

        $this->warnings = array();
    }

    public function createSubmitElement($options = array())
    {
        $options = array_replace(array(
            'attribs' => array(
                'ui-jq' => 'button'
            )
        ), $options);

        $submit = new \Tillikum_Form_Element_Submit(
            'tillikum_submit',
            $options
        );

        return $submit;
    }

    public function addWarning($message)
    {
        $this->warnings[] = $message;

        return $this;
    }

    public function addWarnings(array $warnings)
    {
        foreach ($this->warnings as $warning) {
            $this->addWarning($warning);
        }

        return $this;
    }

    public function clearWarnings()
    {
        $this->warnings = array();

        return $this;
    }

    public function getWarnings()
    {
        return $this->warnings;
    }

    public function setWarnings(array $warnings)
    {
        $this->warnings = $warnings;

        return $this;
    }
}
