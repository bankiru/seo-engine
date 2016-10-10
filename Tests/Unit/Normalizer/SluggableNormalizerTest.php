<?php

namespace Bankiru\Seo\Tests\Unit\Normalizer;

use Bankiru\Seo\Destination\DestinationNormalizer;
use Bankiru\Seo\Destination\Normalizer\SluggableNormalizer;
use Bankiru\Seo\Entity\Sluggable;

class SluggableNormalizerTest extends AbstractNormalizerTest
{
    public function getValidSubjects()
    {
        $expected = 'test-string';

        $subject = $this->prophesize(Sluggable::class);
        $subject->getSlug()->willReturn($expected)->shouldBeCalled();

        return [
            'valid Sluggable' => [$expected, $subject->reveal()],
        ];
    }

    public function getInvalidSubjects()
    {
        return [
            'stdClass'      => [new \stdClass()],
            'scalar int'    => [5],
            'scalar string' => ['5'],
            'array'         => [['6', 5, new \stdClass()],],
        ];
    }

    /** @return DestinationNormalizer */
    protected function createNormalizer()
    {
        return new SluggableNormalizer();
    }
}
