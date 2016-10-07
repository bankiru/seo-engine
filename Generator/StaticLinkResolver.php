<?php

namespace Bankiru\Seo\Generator;

use Bankiru\Seo\Entity\LinkInterface;
use Bankiru\Seo\Exception\LinkResolutionException;

final class StaticLinkResolver implements LinkResolver
{
    /** {@inheritdoc} */
    public function resolve($link)
    {
        if (!$this->supports($link)) {
            throw new LinkResolutionException('Unsupported link type to resolve');
        }

        return [$link];
    }

    /** {@inheritdoc} */
    public function supports($link)
    {
        return $link instanceof LinkInterface;
    }
}
