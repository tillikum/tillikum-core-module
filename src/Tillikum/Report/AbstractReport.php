<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Report;

use Traversable;
use Zend\Stdlib\ArrayUtils;

abstract class AbstractReport implements ReportInterface
{
    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * Returns the empty string
     *
     * @return string
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * Returns the default form class
     *
     * @return string
     */
    public function getFormClass()
    {
        return 'Tillikum\Form\Report\Report';
    }

    /**
     * Returns the empty string
     *
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * @return null
     */
    public function clearParameters()
    {
        $this->parameters = array();
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return mixed
     */
    public function getParameter($parameter)
    {
        if (!isset($this->parameters[$parameter])) {
            return null;
        }

        return $this->parameters[$parameter];
    }

    /**
     * Set parameters for this report
     *
     * This method will (recursively) replace existing parameters, but does not
     * reset them first. Use clearParameters() if you want that behavior.
     *
     * @return AbstractReport
     * @throws Exception\InvalidArgumentException when parameters are not
     *                                            traversable or an array
     */
    public function setParameters($parameters)
    {
        if ($parameters instanceof Traversable) {
            $parameters = ArrayUtils::iteratorToArray($parameters);
        } elseif (!is_array($parameters)) {
            throw new Exception\InvalidArgumentException(
                '$parameters must be an array or a Traversable.'
            );
        }

        $this->parameters = $parameters;

        return $this;
    }
}
