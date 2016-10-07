<?php

namespace Bankiru\Seo\Destination\Compiler;

use Bankiru\Seo\Destination\DestinationNormalizer;
use Symfony\Component\Routing\RouterInterface;

final class SymfonyRouterCompiler extends AbstractCompiler
{
    /** @var  RouterInterface */
    private $router;

    /**
     * SymfonyRouterCompiler constructor.
     *
     * @param DestinationNormalizer $normalizer
     * @param RouterInterface       $router
     */
    public function __construct(DestinationNormalizer $normalizer, RouterInterface $router)
    {
        parent::__construct($normalizer);
        $this->router = $router;
    }

    /** {@inheritdoc} */
    protected function getHrefTemplate($route)
    {
        return new LazyCompilableLink($route, $this->router);
    }
}
