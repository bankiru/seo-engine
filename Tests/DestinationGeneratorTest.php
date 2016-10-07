<?php

namespace Bankiru\Seo\Tests;

use Bankiru\Seo\Exception\DestinationException;
use Bankiru\Seo\Generator\DestinationGenerator;
use Bankiru\Seo\Integration\Local\CallbackFiller;
use Bankiru\Seo\Integration\Local\CollectionSource;
use Doctrine\Common\Collections\ArrayCollection;

class DestinationGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGeneratorCircularityException()
    {
        $source = new CollectionSource(new ArrayCollection([1, 2, 3]));

        $generator = new DestinationGenerator();
        try {
            $generator->generate(
                'random_route_id',
                [
                    'source_arg' => $source,
                ],
                [
                    'filler_1' => $this->createCloneFiller(['filler_2']),
                    'filler_2' => $this->createCloneFiller(['filler_3']),
                    'filler_3' => $this->createCloneFiller(['filler_1']),
                ]
            );
        } catch (DestinationException $exception) {
            return;
        }

        self::fail('Circular dependency was not detected');
    }

    public function testGeneratorInference()
    {
        $route  = 'random_route_id';
        $source = new CollectionSource(new ArrayCollection([1, 2, 3]));

        $generator = new DestinationGenerator();

        $destinations = $generator->generate(
            $route,
            ['source_arg' => $source],
            ['filler_1' => $this->createCloneFiller(['source_arg'])]
        );

        self::assertCount(3, $destinations);
        foreach ($destinations as $destination) {
            self::assertSame($route, $destination->getRoute());
            self::assertNotNull($destination->resolve('source_arg'));
            self::assertNotNull($destination->resolve('filler_1'));
            self::assertSame($destination->resolve('source_arg'), $destination->resolve('filler_1'));
        }
    }

    private function createCloneFiller(array $dependencies)
    {
        return new CallbackFiller(
            $dependencies, function ($items) {
            return array_shift($items);
        }
        );
    }
}
