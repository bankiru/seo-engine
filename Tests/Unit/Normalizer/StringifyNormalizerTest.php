<?php

namespace Bankiru\Seo\Tests\Unit\Normalizer;

use Bankiru\Seo\Destination\Normalizer\StringifyNormalizer;

class StringifyNormalizerTest extends AbstractNormalizerTest
{

    public function getValidSubjects()
    {
        return [
            'Valid subject' => ['test', new StringableMock('test')],
        ];
    }

    public function getInvalidSubjects()
    {
        return [
            'Invalid subject' => [[]],
        ];
    }

    /** {@inheritdoc} */
    protected function createNormalizer()
    {
        return new StringifyNormalizer();
    }
}
