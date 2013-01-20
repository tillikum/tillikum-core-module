<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Application\Resource;

use Zend_Application_Resource_ResourceAbstract as ResourceAbstract;

class Form extends ResourceAbstract
{
    public function init()
    {
        $options = $this->getOptions();

        \Tillikum_Form::setDefaultOptions($options);

        return $this;
    }
}
