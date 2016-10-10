<?php

namespace Bankiru\Seo\Destination\Normalizer;

use Bankiru\Seo\Destination\DestinationNormalizer;
use Bankiru\Seo\Exception\DestinationException;

final class ScalarNormalizer implements DestinationNormalizer
{
    /** {@inheritdoc} */
    public function normalize($item)
    {
        if (!$this->supports($item)) {
            throw DestinationException::normalizationFailed($item, 'scalar');
        }

        return (string)$item;
    }

    /** {@inheritdoc} */
    public function supports($item)
    {
        return is_scalar($item);
    }
}
