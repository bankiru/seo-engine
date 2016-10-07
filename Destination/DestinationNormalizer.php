<?php

namespace Bankiru\Seo\Destination;

use Bankiru\Seo\Exception\DestinationException;

interface DestinationNormalizer
{
    /**
     * @param mixed $item
     *
     * @return string normalized item for URL generation
     * @throws DestinationException
     */
    public function normalize($item);

    /**
     * @param $item
     *
     * @return bool
     */
    public function supports($item);
}
