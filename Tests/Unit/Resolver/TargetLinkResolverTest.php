<?php

namespace Bankiru\Seo\Tests\Unit\Resolver;

use Bankiru\Seo\Destination\CartesianDestinationGenerator;
use Bankiru\Seo\Destination\Compiler\SymfonyRouterCompiler;
use Bankiru\Seo\Destination\Normalizer\DelegatingNormalizer;
use Bankiru\Seo\Destination\Normalizer\ScalarNormalizer;
use Bankiru\Seo\Entity\LinkInterface;
use Bankiru\Seo\Entity\TargetDefinition;
use Bankiru\Seo\Integration\Local\CallbackFiller;
use Bankiru\Seo\Integration\Local\CollectionSource;
use Bankiru\Seo\Integration\Local\TargetLink;
use Bankiru\Seo\Resolver\SourceRegistry;
use Bankiru\Seo\Resolver\TargetLinkResolver;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class TargetLinkResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testLinksGenerating()
    {
        $route = 'random_route_id';

        $target = new TargetDefinition($route);

        $registry = new SourceRegistry();
        $registry->add(
            'my_source',
            new CollectionSource(
                new ArrayCollection(
                    [
                        'quas',
                        'wex',
                        'exort',
                    ]
                )
            )
        );

        $normalizer = new DelegatingNormalizer([new ScalarNormalizer()]);
        $collection = new RouteCollection();
        $collection->add(
            $route,
            new Route('/my/{source}')
        );
        $generator = new UrlGenerator($collection, new RequestContext());

        $compiler = new SymfonyRouterCompiler($normalizer, null, $generator);

        $filler = new CallbackFiller(
            ['source'],
            function ($items) {
                return 'filled_' . array_shift($items);
            }
        );

        $link = new TargetLink(
            $target,
            'My link {{ source }} {{ filler}}',
            ['source' => 'my_source'],
            ['filler' => $filler]
        );

        $resolver = new TargetLinkResolver($registry, $compiler, new CartesianDestinationGenerator());
        /** @var LinkInterface[] $links */
        $links = $resolver->resolve($link);

        self::assertCount(3, $links);
        $res = [];
        foreach ($links as $compiledLink) {
            $res[$compiledLink->getHref()] = $compiledLink->getTitle();
        }

        self::assertArrayHasKey('/my/quas?filler=filled_quas', $res);
        self::assertArrayHasKey('/my/wex?filler=filled_wex', $res);
        self::assertArrayHasKey('/my/exort?filler=filled_exort', $res);
        self::assertEquals('My link quas filled_quas', $res['/my/quas?filler=filled_quas']);
        self::assertEquals('My link wex filled_wex', $res['/my/wex?filler=filled_wex']);
        self::assertEquals('My link exort filled_exort', $res['/my/exort?filler=filled_exort']);
    }

}
