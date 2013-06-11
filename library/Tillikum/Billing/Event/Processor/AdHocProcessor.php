<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Billing\Event\Processor;

use Doctrine\Common\Collections\ArrayCollection;
use Tillikum\Billing\Event\Strategy\StrategyInterface;
use Tillikum\Entity\Billing\Entry\Entry;
use Tillikum\Entity\Billing\Event\Event;
use Vo\DateRange;

class AdHocProcessor extends AbstractProcessor
{
    /**
     * @param  Event   $event
     * @return Entry[]
     *
     * @throws Exception\RuntimeException
     * @throws Zend\Di\Exception\ClassNotFoundException
     */
    public function process(Event $event)
    {
        $entries = new ArrayCollection();

        $eventEffective = $event->effective;

        $rule = $event->rule;

        if ($rule === null) {
            throw new Exception\RuntimeException(
                sprintf(
                    'The event %s has no associated rule.',
                    $event->id
                )
            );
        }

        $config = $this->getMatchingConfiguration(
            $rule,
            $eventEffective
        );

        $strategy = $this->di->get($config->strategy);

        if (!$strategy instanceof StrategyInterface) {
            throw new Exception\RuntimeException(
                sprintf(
                    'The class %s does not implement %s.',
                    get_class($strategy),
                    'Tillikum\Billing\Event\Strategy\StrategyInterface'
                )
            );
        }

        $strategyEntries = $strategy->process($event, $config);

        foreach ($strategyEntries as $entry) {
            $entries->add($entry);
        }

        return $entries;
    }
}
