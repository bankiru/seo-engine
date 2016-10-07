<?php

namespace Bankiru\Seo\Destination\Normalizer;

use Bankiru\Seo\Destination\DestinationNormalizer;
use Bankiru\Seo\Exception\DestinationException;

final class StringifyNormalizer implements DestinationNormalizer
{
    /** {@inheritdoc} */
    public function normalize($item)
    {
        if (!$this->supports($item)) {
            throw DestinationException::normalizationFailed($item);
        }

        return (string)$item;
    }

    /** {@inheritdoc} */
    public function supports($item)
    {
        return is_object($item) && method_exists($item, '__toString');
    }
}
