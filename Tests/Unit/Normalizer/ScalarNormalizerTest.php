<?php

namespace Bankiru\Seo\Tests\Unit\Normalizer;

use Bankiru\Seo\Destination\DestinationNormalizer;
use Bankiru\Seo\Destination\Normalizer\ScalarNormalizer;

class ScalarNormalizerTest extends AbstractNormalizerTest
{

    public function getValidSubjects()
    {
        return [
            'integer' => ['5', 5],
            'string'  => ['string', 'string'],
        ];
    }

    public function getInvalidSubjects()
    {
        return [
            'object' => [new \stdClass()],
            'array'  => [[]],
        ];
    }

    /** @return DestinationNormalizer */
    protected function createNormalizer()
    {
        return new ScalarNormalizer();
    }
}
