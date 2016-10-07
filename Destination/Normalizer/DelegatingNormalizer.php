<?php

namespace Bankiru\Seo\Destination\Normalizer;

use Bankiru\Seo\Destination\DestinationNormalizer;
use Bankiru\Seo\Exception\DestinationException;

final class DelegatingNormalizer implements DestinationNormalizer
{
    /** @var  DestinationNormalizer[] */
    private $normalizers = [];

    /**
     * DelegatingNormalizer constructor.
     *
     * @param DestinationNormalizer[] $normalizers
     */
    public function __construct(array $normalizers)
    {
        $this->normalizers = $normalizers;
    }

    /** {@inheritdoc} */
    public function normalize($item)
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($item)) {
                return $normalizer->normalize($item);
            }
        }

        throw DestinationException::normalizationFailed($item);
    }

    /** {@inheritdoc} */
    public function supports($item)
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($item)) {
                return true;
            }
        }

        return false;
    }
}
