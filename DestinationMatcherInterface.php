<?php

namespace Bankiru\Seo;

use Bankiru\Seo\Exception\MatchingException;
use Bankiru\Seo\Page\SeoPageInterface;

interface DestinationMatcherInterface
{
    /**
     * @param DestinationInterface $destination
     *
     * @return SeoPageInterface
     * @throws MatchingException
     */
    public function match(DestinationInterface $destination);
}
