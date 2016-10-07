<?php

namespace Bankiru\Seo;

use Bankiru\Seo\Page\SeoPageInterface;

interface ProcessorInterface
{
    /**
     * @param DestinationInterface $destination
     *
     * @return null|SeoPageInterface
     */
    public function process(DestinationInterface $destination);
}
