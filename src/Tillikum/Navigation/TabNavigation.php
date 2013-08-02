<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Navigation;

class TabNavigation extends \Zend_Navigation
{
    protected $options;

    public function __construct($pages = null)
    {
        parent::__construct($pages);

        $this->options = array(
            'active' => 0,
            'collapsible' => false,
            'disabled' => false,
            'event' => 'click',
            'heightStyle' => 'content',
            'hide' => null,
            'show' => null,
        );
    }

    public function setOptions(array $options)
    {
        $this->options = array_replace_recursive(
            $this->options,
            $options
        );

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
