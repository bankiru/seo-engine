<?php

namespace Bankiru\Seo\Tests\Unit\Resolver;

use Bankiru\Seo\CompiledLink;
use Bankiru\Seo\Resolver\StaticLinkResolver;

class StaticLinkResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testStaticLinkResolution()
    {
        $resolver = new StaticLinkResolver();

        $link  = new CompiledLink('/test/', 'Title');
        $links = $resolver->resolve($link);

        self::assertInternalType('array', $links);
        self::assertContains($link, $links);
        self::assertCount(1, $links);
    }
}
