<?php

namespace Bankiru\Seo\Tests\Unit\Page;

use Bankiru\Seo\Page\DynamicSeoPageBuilder;
use Bankiru\Seo\Page\NameMeta;

class DynamicBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testPageBuilding()
    {
        $builder = DynamicSeoPageBuilder
            ::create()
            ->setTitle('Test title {{ ph }}')
            ->setCanonicalLink('/test/{{ ph }}')
            ->setMeta([NameMeta::keywords(['test {{ ph }}', '{{ ph }} bankiru', 'seo-engine {{ ph }}'])]);

        $builder->setContext(['ph' => '__dynamic__']);

        $page = $builder->getSeoPage();

        self::assertEquals('Test title __dynamic__', $page->getTitle());
        self::assertEquals('/test/__dynamic__', $page->getCanonicalLink());
        self::assertCount(1, $page->getMeta());
        $meta = $page->getMeta()[0];
        self::assertEquals('keywords', $meta->getName());
        self::assertEquals('test __dynamic__,__dynamic__ bankiru,seo-engine __dynamic__', $meta->getContent());
        self::assertNull($meta->getHttpEquiv());
    }

    public function testPageBuilderIsDetachedFromPage()
    {
        $builder = DynamicSeoPageBuilder::create();
        $builder->setContext(['ph' => '__dynamic__']);

        $builder->setTitle('Test1 {{ ph }}');
        $page1 = $builder->getSeoPage();

        $builder->setTitle('Test2 {{ ph }}');
        $page2 = $builder->getSeoPage();

        self::assertNotSame($page1, $page2);
        self::assertEquals('Test1 __dynamic__', $page1->getTitle());
        self::assertEquals('Test2 __dynamic__', $page2->getTitle());
    }
}
