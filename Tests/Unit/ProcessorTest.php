<?php

namespace Bankiru\Seo\Tests\Unit;

use Bankiru\Seo\Destination;
use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\DestinationMatcher;
use Bankiru\Seo\Entity\TargetDefinition;
use Bankiru\Seo\Exception\MatchingException;
use Bankiru\Seo\Integration\Local\ExactCondition;
use Bankiru\Seo\Page\SeoPageBuilder;
use Bankiru\Seo\PageRepositoryInterface;
use Bankiru\Seo\Target\MatchScoreTargetSorter;
use Bankiru\Seo\TargetDefinitionInterface;
use Bankiru\Seo\TargetRepositoryInterface;
use Prophecy\Argument;

class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function getItems()
    {
        $route = 'any_random_route_id';
        $c1    = new ExactCondition('1');
        $c2    = new ExactCondition(2);

        $d1      = new TargetDefinition($route);
        $d2      = new TargetDefinition($route);
        $d3      = new TargetDefinition($route);
        $d4      = new TargetDefinition($route);
        $targets = [$d1, $d2, $d3, $d4];

        foreach ($targets as $target) {
            $c1->attach($target, 'arg1');
            $c2->attach($target, 'arg2');
        }

        return [
            'exact match'   => [$route, $targets, ['arg1' => '1', 'arg2' => 2], true],
            'arg1 no match' => [$route, $targets, ['arg1' => 2, 'arg2' => 2], false],
            'arg2 no match' => [$route, $targets, ['arg1' => '1', 'arg2' => 1], false],
            'no match'      => [$route, $targets, ['arg1' => '5', 'arg2' => 3], false],
        ];
    }

    /**
     * @dataProvider getItems
     *
     * @param string $route
     * @param array  $targets
     * @param array  $items
     *
     * @internal     param $route
     */
    public function testProcessing($route, array $targets, array $items, $match)
    {

        $page = (new SeoPageBuilder())
            ->setTitle('Test Page')
            ->getSeoPage();

        $targetRepository = $this->prophesize(TargetRepositoryInterface::class);
        $targetRepository->findByRoute(Argument::exact($route))->willReturn($targets);
        $targetRepository = $targetRepository->reveal();

        $pageRepository = $this->prophesize(PageRepositoryInterface::class);
        $pageRepository
            ->getByTargetDestination(
                Argument::type(TargetDefinitionInterface::class),
                Argument::type(DestinationInterface::class)
            )
            ->willReturn($page);
        $pageRepository = $pageRepository->reveal();

        $processor = new DestinationMatcher(new MatchScoreTargetSorter($targetRepository), $pageRepository);

        $destination = new Destination($route, $items);

        try {
            $seoPage = $processor->match($destination);
            if (!$match) {
                self::fail('Should not match');
            }
            self::assertEquals($page, $seoPage);
        } catch (MatchingException $exception) {
            if ($match) {
                throw $exception;
            }
        }
    }
}
