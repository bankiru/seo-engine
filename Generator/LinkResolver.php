<?php

namespace Bankiru\Seo\Generator;

use Bankiru\Seo\Entity\LinkInterface;
use Bankiru\Seo\Exception\LinkResolutionException;

interface LinkResolver
{
    /**
     * @param $link
     *
     * @return LinkInterface[]
     *
     * @throws LinkResolutionException
     */
    public function resolve($link);

    /**
     * @param $link
     *
     * @return bool
     */
    public function supports($link);
}
