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
 * Occupancy brace-counting engine
 *
 * @link http://stackoverflow.com/a/7469347
 */
class Engine
{
    protected $inputs;

    /**
     * Constructor
     *
     * @param Input[] $inputs
     */
    public function __construct(array $inputs)
    {
        foreach ($inputs as $input) {
            if (!$input instanceof Input) {
                throw new Exception\InvalidArgumentException(
                    sprintf(
                        '%s is not a valid %s',
                        is_object($input) ? get_class($input) : gettype($input),
                        __NAMESPACE__ . '\\' . 'Input'
                    )
                );
            }
        }

        $this->inputs = $inputs;
    }

    /**
     * Run configured engine and return a result
     *
     * @return Result
     */
    public function run()
    {
        usort($this->inputs, array($this, 'sortInputs'));

        $count = 0;
        foreach ($this->inputs as $input) {
            $count += $input->getValue();

            if ($count < 0) {
                return new Result(false, $input);
            }
        }

        return new Result(true);
    }

    /**
     * Callback for sorting inputs by date then value
     *
     * @param  Input $a
     * @param  Input $b
     * @return int
     */
    protected function sortInputs($a, $b)
    {
        if ($a->getDate() == $b->getDate()) {
            if ($a->getValue() == $b->getValue()) {
                return 0;
            }

            // Positive values come first, so same-day start/ends are
            // incremented before they are decremented
            return $a->getValue() > $b->getValue() ? -1 : 1;
        }

        return $a->getDate() < $b->getDate() ? -1 : 1;
    }
}
