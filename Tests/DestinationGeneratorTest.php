<?php

namespace Bankiru\Seo\Tests;

use Bankiru\Seo\Destination\CartesianDestinationGenerator;
use Bankiru\Seo\Exception\DestinationException;
use Bankiru\Seo\Integration\Local\CallbackFiller;
use Bankiru\Seo\Integration\Local\CollectionSource;
use Bankiru\Seo\Integration\Local\ExactCondition;
use Doctrine\Common\Collections\ArrayCollection;

class DestinationGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGeneratorCircularityException()
    {
        $generator = new CartesianDestinationGenerator();
        try {
            $generator->generate(
                'random_route_id',
                [
                    'source_arg' => new CollectionSource(new ArrayCollection([1, 2, 3])),
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
        $route     = 'random_route_id';
        $generator = new CartesianDestinationGenerator();

        $destinations = $generator->generate(
            $route,
            ['source_arg' => new CollectionSource(new ArrayCollection([1, 2, 3]))],
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

    public function testCartesianTransform()
    {
        $route     = 'random_route_id';
        $generator = new CartesianDestinationGenerator();

        $destinations = $generator->generate(
            $route,
            [
                'source_arg'   => new CollectionSource(new ArrayCollection([1, 2, 3])),
                'source_arg_2' => new CollectionSource(new ArrayCollection([1, 2, 3])),
            ]
        );

        self::assertCount(9, $destinations);
        foreach ($destinations as $destination) {
            self::assertSame($route, $destination->getRoute());
            self::assertNotNull($destination->resolve('source_arg'));
            self::assertNotNull($destination->resolve('source_arg_2'));
        }
    }

    public function testFilteredCartesianTransform()
    {
        $route     = 'random_route_id';
        $generator = new CartesianDestinationGenerator();

        $source1 = new CollectionSource(new ArrayCollection([1, 2, 3]));
        $source2 = new CollectionSource(new ArrayCollection([1, 2, 3]));

        $source1->withCondition(new ExactCondition(1));
        $source2->withCondition(new ExactCondition(3));

        $destinations = $generator->generate(
            $route,
            [
                'source_arg'   => $source1,
                'source_arg_2' => $source2,
            ]
        );

        self::assertCount(1, $destinations);
        $destination = array_shift($destinations);
        self::assertSame($route, $destination->getRoute());
        self::assertSame(1, $destination->resolve('source_arg'));
        self::assertSame(3, $destination->resolve('source_arg_2'));
    }

    private function createCloneFiller(array $dependencies)
    {
        return new CallbackFiller(
            $dependencies,
            function ($items) {
                return array_shift($items);
            }
        );
    }
}
