<?php

namespace Bankiru\Seo\Destination;

use Bankiru\Seo\Exception\LinkGeneratorException;

interface CompilableLinkInterface
{
    /**
     * @param string[] $items
     *
     * @return string
     * @throws LinkGeneratorException
     */
    public function compile(array $items);
}
