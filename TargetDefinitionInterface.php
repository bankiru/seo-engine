<?php

namespace Bankiru\Seo;

use Bankiru\Seo\Page\SeoPageInterface;

interface TargetDefinitionInterface
{
    /**
     * Checks whether the destination matches this target space definition. Returns the match weight if match, null otherwise
     *
     * @param  DestinationInterface $destination
     *
     * @return int|null
     */
    public function match(DestinationInterface $destination);

    /**
     * Defines the condition for the placeholder
     *
     * @param string             $code
     * @param ConditionInterface $condition
     */
    public function setCondition($code, ConditionInterface $condition);

    /**
     * Returns condition setup for code
     *
     * @param string $code
     *
     * @return ConditionInterface
     */
    public function getCondition($code);

    /**
     * Returns route bound to target space
     *
     * @return string
     */
    public function getRoute();

    /**
     * Returns page data relevant to this target
     *
     * @return SeoPageInterface
     */
    public function getSeoPage();
}
