<?php

namespace Bankiru\Seo\Tests\Unit\Normalizer;

use Bankiru\Seo\Destination\DestinationNormalizer;
use Bankiru\Seo\Exception\DestinationException;

abstract class AbstractNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getValidSubjects
     *
     * @param string $expected
     * @param mixed  $subject
     */
    public function testValidObjectNormalization($expected, $subject)
    {
        $normalizer = $this->createNormalizer();

        self::assertTrue($normalizer->supports($subject));
        self::assertEquals($expected, $normalizer->normalize($subject));
    }

    /**
     * @dataProvider getInvalidSubjects
     *
     * @param mixed $subject
     */
    public function testUnsupportedNormalizerThrowsException($subject)
    {
        $normalizer = $this->createNormalizer();
        self::assertFalse($normalizer->supports($subject));

        try {
            $normalizer->normalize($subject);
        } catch (DestinationException $exception) {
            return;
        }

        self::fail('DestinationException is not thrown on invalid normalization');
    }

    abstract public function getValidSubjects();

    abstract public function getInvalidSubjects();

    /** @return DestinationNormalizer */
    abstract protected function createNormalizer();
}
