<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Billing\Event\Processor;

use DateTime;
use Tillikum\Entity\Billing\Event\Event;
use Vo\DateRange;
use Zend\Di;

abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * Dependency injection container
     *
     * Used in construction of billing strategies.
     *
     * @var Di\Di
     */
    protected $di;

    /**
     * @param Di\Di $di
     */
    public function __construct(Di\Di $di)
    {
        $this->di = $di;
    }

    /**
     * Get last matching rule configuration.
     */
    protected function getMatchingConfiguration($rule, DateTime $date)
    {
        $overlapFilter = function ($config) use ($date) {
            $configRange = new DateRange(
                $config->start,
                $config->end
            );

            return $configRange->includes($date);
        };

        $overlappingConfigs = $rule->configs->filter($overlapFilter);

        if (count($overlappingConfigs) === 0) {
            throw new Exception\RuntimeException(
                sprintf(
                    'Could not find a rule configuration valid on %s'
                  . ' for rule %s.',
                    $date->format('Y-m-d'),
                    $rule->id
                )
            );
        }

        // Ordering is guaranteed by the Entity collection specification and
        // preserved by ArrayCollection#filter.
        return $overlappingConfigs->last();
    }
}
