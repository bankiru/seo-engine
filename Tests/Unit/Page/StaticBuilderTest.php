<?php

namespace Bankiru\Seo\Tests\Unit\Page;

use Bankiru\Seo\Page\NameMeta;
use Bankiru\Seo\Page\SeoPageBuilder;

class StaticBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testPageBuilding()
    {
        $builder = SeoPageBuilder
            ::create()
            ->setTitle('Test title')
            ->setCanonicalLink('/test')
            ->setMeta([NameMeta::keywords(['test', 'bankiru', 'seo-engine'])]);

        $page = $builder->getSeoPage();

        self::assertEquals('Test title', $page->getTitle());
        self::assertEquals('/test', $page->getCanonicalLink());
        self::assertCount(1, $page->getMeta());
        $meta = $page->getMeta()[0];
        self::assertEquals('keywords', $meta->getName());
        self::assertEquals('test,bankiru,seo-engine', $meta->getContent());
        self::assertNull($meta->getHttpEquiv());
    }

    public function testPageBuilderIsDetachedFromPage()
    {
        $builder = SeoPageBuilder::create();

        $builder->setTitle('Test1');
        $page1 = $builder->getSeoPage();

        $builder->setTitle('Test2');
        $page2 = $builder->getSeoPage();

        self::assertNotSame($page1, $page2);
        self::assertEquals('Test1', $page1->getTitle());
        self::assertEquals('Test2', $page2->getTitle());
    }
}
