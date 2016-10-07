<?php

namespace Bankiru\Seo\Context;

use Bankiru\Seo\DestinationInterface;

final class ContextExtractor
{
    public function extractContext(DestinationInterface $destination)
    {
        return array_map([$this, 'convert'], iterator_to_array($destination));
    }

    private function convert($item)
    {
        if ($item instanceof ContextItemInterface) {
            return $item->getContext();
        }

        return $item;
    }
}
