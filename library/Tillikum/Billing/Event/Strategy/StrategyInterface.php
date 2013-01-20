<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Billing\Event\Strategy;

use Tillikum\Entity\Billing\Rule\Config\Config as RuleConfig;
use Tillikum\Entity\Billing\Event\Event;

interface StrategyInterface
{
    /**
     * Return the description for this strategy.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Return the name for this strategy.
     *
     * @return string
     */
    public function getName();

    /**
     * Process the event according to the given rule configuration.
     *
     * @param Event      $event
     * @param RuleConfig $config
     */
    public function process(Event $event, RuleConfig $config);
}
