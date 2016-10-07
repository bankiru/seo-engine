<?php

namespace Bankiru\Seo\Destination\Normalizer;

use Bankiru\Seo\Destination\DestinationNormalizer;
use Bankiru\Seo\Entity\Sluggable;
use Bankiru\Seo\Exception\DestinationException;

final class SluggableNormalizer implements DestinationNormalizer
{
    /** {@inheritdoc} */
    public function normalize($item)
    {
        if (!$this->supports($item)) {
            throw DestinationException::normalizationFailed($item, Sluggable::class);
        }

        /** @var Sluggable $item */
        return $item->getSlug();
    }

    /** {@inheritdoc} */
    public function supports($item)
    {
        return $item instanceof Sluggable;
    }
}
