<?php

namespace Bankiru\Seo\Destination;

use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\Entity\LinkInterface;
use Bankiru\Seo\Exception\DestinationException;

interface DestinationCompiler
{
    /**
     * @param DestinationInterface $destination
     * @param string|null          $title
     * @param string[]             $attributes
     *
     * @return LinkInterface
     * @throws DestinationException
     */
    public function compile(DestinationInterface $destination, $title = null, array $attributes = []);
}
