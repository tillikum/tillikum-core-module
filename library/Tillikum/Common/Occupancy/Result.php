<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Common\Occupancy;

/**
 * Occupancy brace-counting algorithm result
 *
 * @see Engine
 */
class Result
{
    /**
     * On failure, this is set to the failing input
     *
     * @var Input
     */
    protected $culprit;

    /**
     * Success or failure flag
     *
     * @var bool
     */
    protected $isSuccess;

    /**
     * Constructor
     *
     * @param bool       $isSuccess
     * @param null|Input $culprit
     */
    public function __construct($isSuccess, $culprit = null)
    {
        $this->culprit = $culprit;
        $this->isSuccess = $isSuccess;
    }

    /**
     * Get failing input
     *
     * @return null|Input
     */
    public function getCulprit()
    {
        return $this->culprit;
    }

    /**
     * Get success or failure flag
     *
     * @return bool
     */
    public function getIsSuccess()
    {
        return $this->isSuccess;
    }
}
