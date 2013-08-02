<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Common\Occupancy;

use DateTime;

/**
 * Occupancy brace-counting engine input
 *
 * @see Engine
 */
class Input
{
    /**
     * Date of the value change
     *
     * @var DateTime
     */
    protected $date;

    /**
     * Description of the value change
     *
     * @var string
     */
    protected $description;

    /**
     * Value of the "brace" that changes
     *
     * @var int
     */
    protected $value;

    /**
     * Constructor
     *
     * @param DateTime $date
     * @param int      $value
     * @param string   $description
     */
    public function __construct(DateTime $date, $value, $description)
    {
        $this->date = clone $date;
        $this->description = $description;
        $this->value = $value;
    }

    /**
     * Get input date
     *
     * @return string
     */
    public function getDate()
    {
        return clone $this->date;
    }

    /**
     * Get input description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get input value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
