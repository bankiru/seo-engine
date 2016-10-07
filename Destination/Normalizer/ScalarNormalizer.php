<?php

namespace Bankiru\Seo\Destination\Normalizer;

use Bankiru\Seo\Destination\DestinationNormalizer;

final class ScalarNormalizer implements DestinationNormalizer
{
    /** {@inheritdoc} */
    public function normalize($item)
    {
        return (string)$item;
    }

    /** {@inheritdoc} */
    public function supports($item)
    {
        return is_scalar($item);
    }
}
