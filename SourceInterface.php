<?php

namespace Bankiru\Seo;

interface SourceInterface extends \Traversable, \Countable
{
    /**
     * @param ConditionInterface $condition
     *
     * @return SourceInterface
     */
    public function withCondition(ConditionInterface $condition);
}
