<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Booking;

use Traversable;

interface PendingBookingInterface
{
    /**
     * @return null
     */
    public function clearParameters();

    /**
     * Returns the controller action helper name for this pending booking list
     *
     * @return string
     */
    public function getActionHelperName();

    /**
     * Return the description for this report.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Return the name for this pending booking list.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the view helper name for this pending booking list
     *
     * @return string
     */
    public function getViewHelperName();

    /**
     * @param  string     $parameter
     * @return null|mixed
     */
    public function getParameter($parameter);

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param  array|Traversable $parameters
     * @return ReportInterface
     */
    public function setParameters($parameters);
}
