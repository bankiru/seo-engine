<?php

namespace Bankiru\Seo;

use Bankiru\Seo\Exception\PageException;
use Bankiru\Seo\Page\SeoPageInterface;

interface PageRepositoryInterface
{
    /**
     * Returns SeoPageInterface for given target (and optionally destination)
     *
     * @param TargetDefinitionInterface $target
     * @param DestinationInterface|null $destination
     *
     * @return SeoPageInterface
     * @throws PageException
     */
    public function getByTargetDestination(TargetDefinitionInterface $target, DestinationInterface $destination = null);
}
