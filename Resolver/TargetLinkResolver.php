<?php

namespace Bankiru\Seo\Resolver;

use Bankiru\Seo\Destination\CartesianDestinationGenerator;
use Bankiru\Seo\Destination\DestinationCompiler;
use Bankiru\Seo\Destination\DestinationGenerator;
use Bankiru\Seo\Entity\TargetLinkInterface;
use Bankiru\Seo\Exception\DestinationException;
use Bankiru\Seo\Exception\LinkResolutionException;
use Bankiru\Seo\SourceInterface;

final class TargetLinkResolver implements LinkResolver
{
    /** @var CartesianDestinationGenerator */
    private $generator;
    /** @var  SourceRegistry */
    private $registry;
    /** @var DestinationCompiler */
    private $compiler;

    /**
     * TargetLinkResolver constructor.
     *
     * @param SourceRegistry       $registry
     * @param DestinationCompiler  $compiler
     * @param DestinationGenerator $generator
     */
    public function __construct(
        SourceRegistry $registry,
        DestinationCompiler $compiler,
        DestinationGenerator $generator = null
    ) {
        $this->registry  = $registry;
        $this->compiler  = $compiler;
        $this->generator = $generator ?: new CartesianDestinationGenerator();
    }

    /** {@inheritdoc} */
    public function resolve($link)
    {
        if (!$this->supports($link)) {
            throw new LinkResolutionException('Unsupported link type to resolve');
        }

        /** @var TargetLinkInterface $link */
        /** @var SourceInterface[] $sources */
        $sources = [];
        try {
            foreach ($link->getSources() as $code => $key) {
                $sources[$code] = $this->registry->get($key);
            }
        } catch (\OutOfBoundsException $exception) {
            throw new LinkResolutionException(
                'Cannot resolve link: ' . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        try {
            foreach ($sources as $code => $source) {
                $source->withCondition($link->getTarget()->getCondition($code));
            }

            $destinations = $this->generator->generate($link->getTarget()->getRoute(), $sources, $link->getFillers());

            $links = [];
            foreach ($destinations as $destination) {
                $links[] = $this->compiler->compile($destination, $link->getTitleTemplate(), []);
            }
        } catch (DestinationException $exception) {
            throw new LinkResolutionException(
                'Error expanding destinations for link: ' . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        return $links;
    }

    /** {@inheritdoc} */
    public function supports($link)
    {
        return $link instanceof TargetLinkInterface;
    }
}
