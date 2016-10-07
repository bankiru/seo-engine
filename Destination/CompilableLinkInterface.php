<?php

namespace Bankiru\Seo\Destination;

interface CompilableLinkInterface
{
    /**
     * @param string[] $items
     *
     * @return string
     */
    public function compile(array $items);
}
