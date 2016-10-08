<?php

namespace Bankiru\Seo\Tests\Bundle;

use Bankiru\Seo\BankiruSeoEngineBundle;
use Bankiru\Seo\Page\SeoPageInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

final class SeoKernel extends Kernel
{
    use MicroKernelTrait;

    /** {@inheritdoc} */
    public function registerBundles()
    {
        return [
            new BankiruSeoEngineBundle(),
            new FrameworkBundle(),
        ];
    }

    public function getCacheDir()
    {
        return __DIR__.'/../../build/cache';
    }

    public function getLogDir()
    {
        return __DIR__.'/../../build/logs';
    }

    public function eventAction(SeoPageInterface $_seo_page = null)
    {
        return new Response($_seo_page ? $_seo_page->getTitle() : 'not_found');
    }

    /**
     * Add or import routes into your application.
     *
     *     $routes->import('config/routing.yml');
     *     $routes->add('/admin', 'AppBundle:Admin:dashboard', 'admin_dashboard');
     *
     * @param RouteCollectionBuilder $routes
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $seoRoute = $routes->add('/test/match', 'kernel:eventAction', 'event_controller_match');
        $seoRoute->setOption('seo', true);
        $seoRoute = $routes->add('/test/no_match', 'kernel:eventAction', 'event_controller_no_match');
        $seoRoute->setOption('seo', true);
        $routes->add('/test/no_seo', 'kernel:eventAction', 'event_controller_no_seo');
    }

    /** {@inheritdoc} */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework', ['secret' => 'test_secret', 'test' => true]);
    }
}
