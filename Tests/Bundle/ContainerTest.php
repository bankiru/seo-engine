<?php

namespace Bankiru\Seo\Tests\Bundle;

use Bankiru\Seo\Destination;
use Bankiru\Seo\Entity\TargetDefinition;
use Bankiru\Seo\Page\SeoPageBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContainerTest extends KernelTestCase
{
    public function testContainerBuilding()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();
        self::assertNotNull($container);

        $route = 'test_route';

        $target      = new TargetDefinition($route);
        $destination = new Destination($route, []);
        $page        = (new SeoPageBuilder())->setTitle('Title')->getSeoPage();

        $targetRepo = $container->get('bankiru.seo.target_repository');
        $pageRepo   = $container->get('bankiru.seo.page_repository');

        $targetRepo->add($target);
        $pageRepo->add($target, $page);

        $matcher = $container->get('bankiru.seo.matcher');
        self::assertSame($page, $matcher->match($destination));

        self::assertTrue($container->has('bankiru.seo.request_listener'));
    }

    protected static function getKernelClass()
    {
        return SeoKernel::class;
    }
}
