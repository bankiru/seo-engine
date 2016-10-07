<?php

namespace Bankiru\Seo;

use Bankiru\Seo\Exception\ProcessingException;
use Bankiru\Seo\Page\SeoPageInterface;

interface ProcessorInterface
{
    /**
     * @param DestinationInterface $destination
     *
     * @return SeoPageInterface
     * @throws ProcessingException
     */
    public function process(DestinationInterface $destination);
}
