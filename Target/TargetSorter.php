<?php

namespace Bankiru\Seo\Target;

use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\Exception\MatchingException;
use Bankiru\Seo\TargetDefinitionInterface;

interface TargetSorter
{
    /**
     * Returns most applicable target for given destination
     *
     * @param DestinationInterface $destination
     *
     * @return TargetDefinitionInterface
     * @throws MatchingException
     */
    public function getMatchingTarget(DestinationInterface $destination);
}
