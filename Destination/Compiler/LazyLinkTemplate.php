<?php

namespace Bankiru\Seo\Destination\Compiler;

use Bankiru\Seo\Destination\CompilableLinkInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

final class LazyCompilableLink implements CompilableLinkInterface
{
    /** @var string */
    private $route;
    /** @var UrlGeneratorInterface */
    private $generator;
    /** @var RouterInterface */
    private $router;

    /**
     * LazyCompilableLink constructor.
     *
     * @param string          $route
     * @param RouterInterface $router
     */
    public function __construct($route, RouterInterface $router)
    {
        $this->route     = $route;
        $this->router    = $router;
        $this->generator = new UrlGenerator($this->router->getRouteCollection(), new RequestContext());
    }

    /** {@inheritdoc} */
    public function compile(array $items)
    {
        return $this->generator->generate($this->route, $items);
    }
}
