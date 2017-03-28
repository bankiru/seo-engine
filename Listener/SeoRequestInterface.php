<?php

namespace Bankiru\Seo\Listener;

use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\Exception\MatchingException;
use Bankiru\Seo\Page\SeoPageInterface;

interface SeoRequestInterface
{
    /**
     * @return DestinationInterface
     * @throws \LogicException
     */
    public function getDestination();

    /**
     * @param DestinationInterface $destination
     */
    public function setDestination(DestinationInterface $destination);

    /**
     * @return SeoPageInterface
     * @throws MatchingException
     */
    public function getPage();

    /**
     * @return SeoPageInterface
     * @throws MatchingException
     */
    public function getRenderedPage();
}
