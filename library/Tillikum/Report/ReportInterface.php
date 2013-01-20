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

interface ReportInterface
{
    /**
     * Generate an array of report rows
     *
     * Headers constitute the first row of the array, the rest should be data.
     *
     * @return array
     */
    public function generate();

    /**
     * Return the description for this report.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Return the form class associated with this report
     *
     * @return string
     */
    public function getFormClass();

    /**
     * Return the name for this report.
     *
     * @return string
     */
    public function getName();

    /**
     * @return null
     */
    public function clearParameters();

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param  string     $parameter
     * @return null|mixed
     */
    public function getParameter($parameter);

    /**
     * @param  array|Traversable $parameters
     * @return ReportInterface
     */
    public function setParameters($parameters);
}
