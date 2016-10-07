<?php

namespace Bankiru\Seo\Tests\Bundle;

use Bankiru\Seo\Destination;
use Bankiru\Seo\Entity\TargetDefinition;
use Bankiru\Seo\Integration\Local\StaticPageRepository;
use Bankiru\Seo\Integration\Local\StaticTargetRepository;
use Bankiru\Seo\Page\SeoPageBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContainerTest extends KernelTestCase
{
    protected static function getKernelClass()
    {
        return SeoKernel::class;
    }

    public function testContainerBuilding()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();
        self::assertNotNull($container);

        $route = 'test_route';

        $target      = new TargetDefinition($route);
        $destination = new Destination($route, []);
        $page        = (new SeoPageBuilder())->setTitle('Title')->getSeoPage();

        $targetRepo = new StaticTargetRepository();
        $pageRepo   = new StaticPageRepository();
        $container->set('bankiru.seo.target_repository', $targetRepo);
        $container->set('bankiru.seo.page_repository', $pageRepo);

        $targetRepo->add($target);
        $pageRepo->add($target, $page);

        $matcher = $container->get('bankiru.seo.matcher');
        self::assertSame($page, $matcher->match($destination));
    }
}
