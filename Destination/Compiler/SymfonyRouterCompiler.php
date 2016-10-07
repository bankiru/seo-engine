<?php

namespace Bankiru\Seo\Destination\Compiler;

use Bankiru\Seo\Destination\DestinationNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SymfonyRouterCompiler extends AbstractCompiler
{
    /** @var  UrlGeneratorInterface */
    private $generator;

    /**
     * SymfonyRouterCompiler constructor.
     *
     * @param DestinationNormalizer $normalizer
     * @param \Twig_Environment     $twig
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(
        DestinationNormalizer $normalizer,
        \Twig_Environment $twig = null,
        UrlGeneratorInterface $generator
    ) {
        parent::__construct($normalizer, $twig);
        $this->generator = $generator;
    }

    /** {@inheritdoc} */
    protected function getHrefTemplate($route)
    {
        return new LazyCompilableLink($route, $this->generator);
    }
}
