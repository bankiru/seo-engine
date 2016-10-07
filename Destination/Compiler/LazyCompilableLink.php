<?php

namespace Bankiru\Seo\Destination\Compiler;

use Bankiru\Seo\Destination\CompilableLinkInterface;
use Bankiru\Seo\Exception\LinkGeneratorException;
use Symfony\Component\Routing\Exception\ExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class LazyCompilableLink implements CompilableLinkInterface
{
    /** @var string */
    private $route;
    /** @var UrlGeneratorInterface */
    private $generator;

    /**
     * LazyCompilableLink constructor.
     *
     * @param string                $route
     * @param UrlGeneratorInterface $generator
     */
    public function __construct($route, UrlGeneratorInterface $generator)
    {
        $this->route     = $route;
        $this->generator = $generator;
    }

    /** {@inheritdoc} */
    public function compile(array $items)
    {
        try {
            return $this->generator->generate($this->route, $items);
        } catch (ExceptionInterface $e) {
            throw new LinkGeneratorException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
