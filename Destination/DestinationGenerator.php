<?php
namespace Bankiru\Seo\Destination;

use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\Exception\DestinationException;
use Bankiru\Seo\SourceFiller;
use Bankiru\Seo\SourceInterface;

interface DestinationGenerator
{
    /**
     * @param string            $route
     * @param SourceInterface[] $sources
     * @param SourceFiller[]    $fillers
     *
     * @return DestinationInterface[]
     * @throws DestinationException
     */
    public function generate($route, array $sources, array $fillers = []);

    /**
     * @param \Countable[] $sources
     *
     * @return int
     */
    public function count(array $sources);
}
