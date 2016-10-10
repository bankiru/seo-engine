<?php

namespace Bankiru\Seo\Tests\Unit;

use Bankiru\Seo\Destination;
use Bankiru\Seo\DestinationMatcher;
use Bankiru\Seo\Entity\TargetDefinition;
use Bankiru\Seo\Exception\MatchingException;
use Bankiru\Seo\Integration\Local\ExactCondition;
use Bankiru\Seo\Integration\Local\StaticPageRepository;
use Bankiru\Seo\Integration\Local\StaticTargetRepository;
use Bankiru\Seo\Page\SeoPageBuilder;
use Bankiru\Seo\Page\SeoPageInterface;
use Bankiru\Seo\Target\MatchScoreTargetSorter;

class MatcherTest extends \PHPUnit_Framework_TestCase
{
    /** @var  StaticTargetRepository */
    private $targetRepo;

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

    public function getItemsForNoPage()
    {
        $route = 'any_random_route_id';

        return [
            'exact match' => [$route, [new TargetDefinition($route)], ['arg1' => '1', 'arg2' => 2]],
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
    public function testMatching($route, array $targets, array $items, $match)
    {
        $page = (new SeoPageBuilder())
            ->setTitle('Test Page')
            ->getSeoPage();

        $processor = $this->createProcessor($targets, $page);

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

    /**
     * @expectedException \Bankiru\Seo\Exception\MatchingException
     * @expectedExceptionMessage Destination does not match any target
     */
    public function testRouteMismatch()
    {
        $route        = '/test/';
        $d1           = new TargetDefinition($route);
        $page         = (new SeoPageBuilder())
            ->setTitle('Test Page')
            ->getSeoPage();
        $invalidRoute = $route.'_invalid';

        $processor   = $this->createProcessor([$d1], $page);
        $destination = new Destination($invalidRoute, ['arg1' => 2, 'arg2' => 2]);
        
        $processor->match($destination);
    }

    /**
     * @dataProvider getItemsForNoPage
     *
     * @param string $route
     * @param array  $targets
     * @param array  $items
     *
     * @internal     param $route
     */
    public function testNoPageFound($route, array $targets, array $items)
    {
        $processor = $this->createProcessor($targets);

        $destination = new Destination($route, $items);

        try {
            $page = $processor->match($destination);
            self::assertNotNull($page);
            self::fail('Page should be missing');
        } catch (MatchingException $exception) {
        }
    }

    /**
     * @param TargetDefinition[] $targets
     * @param SeoPageInterface   $page
     *
     * @return DestinationMatcher
     */
    private function createProcessor(array $targets, $page = null)
    {
        $this->targetRepo = $targetRepository = new StaticTargetRepository();
        foreach ($targets as $target) {
            $targetRepository->add($target);
        }


        $pageRepository = new StaticPageRepository();
        if ($page) {
            foreach ($targets as $target) {
                $pageRepository->add($target, $page);
            }
        }

        $processor = new DestinationMatcher(new MatchScoreTargetSorter($targetRepository), $pageRepository);

        return $processor;
    }
}
