<?php

namespace Bankiru\Seo\Tests\Unit\Cases;

use Bankiru\Seo\Destination;
use Bankiru\Seo\DestinationMatcher;
use Bankiru\Seo\Entity\TargetDefinition;
use Bankiru\Seo\Exception\MatchingException;
use Bankiru\Seo\Integration\Local\CallbackCondition;
use Bankiru\Seo\Integration\Local\StaticPageRepository;
use Bankiru\Seo\Integration\Local\StaticTargetRepository;
use Bankiru\Seo\Page\SeoPageBuilder;
use Bankiru\Seo\Target\MatchScoreTargetSorter;
use Bankiru\Seo\Tests\Unit\Cases\Fixtures\Brand;
use Bankiru\Seo\Tests\Unit\Cases\Fixtures\Tag;
use Bankiru\Seo\Tests\Unit\Cases\Fixtures\Type;

class MultipleRoutesMatchingTest extends \PHPUnit_Framework_TestCase
{
    const ROUTES = [
        'route_void' => '/mobile',
        'route_one_part' => '/mobile/{part1}',
        'route_two_parts' => '/mobile/{part1}/{part2}',
    ];

    public function getData()
    {
        return [
            'route_void should match index page' => ['the INDEX page', 'route_void', null, null],
            'route_one_part should match tag page' => ['the TAG page', 'route_one_part', new Tag('cheap'), null],
            'route_one_part should match brand page' => ['the BRAND page', 'route_one_part', new Brand('apple'), null],
            'route_two_parts should match brand/type page' => ['the BRAND/TYPE page', 'route_two_parts', new Brand('apple'), new Type('cellphone')],
            'route_two_parts should match type/brand page' => ['the TYPE/BRAND page', 'route_two_parts', new Type('cellphone'), new Brand('apple')],
        ];
    }

    /**
     * @dataProvider getData
     *
     * @param string $title
     * @param string $route
     * @param mixed $part1
     * @param mixed $part2
     */
    public function testPageTitleMatching($title, $route, $part1, $part2)
    {
        $items = array_filter([
            'part1' => $part1,
            'part2' => $part2
        ]);
        $destination = new Destination($route, $items);
        self::assertEquals($title, $this->createMatcher()->match($destination)->getTitle());
    }

    /**
     * @return DestinationMatcher
     */
    private function createMatcher()
    {
        $repository = new StaticTargetRepository();

        $routeIndexDefinition = new TargetDefinition('route_void');
        $repository->add($routeIndexDefinition);

        $routeTagDefinition = new TargetDefinition('route_one_part');
        $this->createClassCallback(Tag::class)->attach($routeTagDefinition, 'part1');
        $repository->add($routeTagDefinition);

        $routeBrandDefinition = new TargetDefinition('route_one_part');
        $this->createClassCallback(Brand::class)->attach($routeBrandDefinition, 'part1');
        $repository->add($routeBrandDefinition);

        $routeBrandTypeDefinition = new TargetDefinition('route_two_parts');
        $this->createClassCallback(Brand::class)->attach($routeBrandTypeDefinition, 'part1');
        $this->createClassCallback(Type::class)->attach($routeBrandTypeDefinition, 'part2');
        $repository->add($routeBrandTypeDefinition);

        $routeTypeBrandDefinition = new TargetDefinition('route_two_parts');
        $this->createClassCallback(Type::class)->attach($routeTypeBrandDefinition, 'part1');
        $this->createClassCallback(Brand::class)->attach($routeTypeBrandDefinition, 'part2');
        $repository->add($routeTypeBrandDefinition);

        $sorter = new MatchScoreTargetSorter($repository);
        $pageRepository = new StaticPageRepository();

        $pageRepository->add($routeTagDefinition, $this->buildPageFor('the TAG page'));
        $pageRepository->add($routeBrandDefinition, $this->buildPageFor('the BRAND page'));
        $pageRepository->add($routeIndexDefinition, $this->buildPageFor('the INDEX page'));
        $pageRepository->add($routeBrandTypeDefinition, $this->buildPageFor('the BRAND/TYPE page'));
        $pageRepository->add($routeTypeBrandDefinition, $this->buildPageFor('the TYPE/BRAND page'));

        return new DestinationMatcher($sorter, $pageRepository);
    }

    private function createClassCallback($class)
    {
        return new CallbackCondition(
            function ($object) use ($class) {
                return $object instanceof $class;
            }
        );
    }



    private function buildPageFor($title)
    {
        $builder = new SeoPageBuilder();
        $builder->setTitle($title);

        return $builder->getSeoPage();
    }

    public function testMultipleRoutesMatched()
    {
        $matcher = $this->createMatcher();

        try {
            $matcher->match(new Destination('route_one_part', ['part1' => new Type('test')]))->getTitle();
            self::fail('Tag page should not match');
        } catch (MatchingException $exception) {
        }

        try {
            $matcher->match(new Destination('route_two_parts', ['part1' => new Tag('cellphone'), 'part2' => new Brand('apple')]))->getTitle();
            self::fail('Tag page should not match');
        } catch (MatchingException $exception) {
        }

        try {
            $matcher->match(new Destination('route_two_parts', ['part1' => new Brand('apple'), 'part2' => new Tag('cellphone')]))->getTitle();
            self::fail('Tag page should not match');
        } catch (MatchingException $exception) {
        }
    }
}
