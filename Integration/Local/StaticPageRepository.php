<?php

namespace Bankiru\Seo\Integration\Local;

use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\Exception\PageException;
use Bankiru\Seo\Page\SeoPageInterface;
use Bankiru\Seo\PageRepositoryInterface;
use Bankiru\Seo\TargetDefinitionInterface;

final class StaticPageRepository implements PageRepositoryInterface
{
    /** @var SeoPageInterface[] */
    private $pages = [];

    public function add(TargetDefinitionInterface $target, SeoPageInterface $page)
    {
        $this->pages[$target->getRoute()] = $page;
    }

    /** {@inheritdoc} */
    public function getByTargetDestination(TargetDefinitionInterface $target, DestinationInterface $destination = null)
    {
        if (!array_key_exists($target->getRoute(), $this->pages)) {
            throw PageException::notFound($target);
        }

        return $this->pages[$target->getRoute()];
    }
}
