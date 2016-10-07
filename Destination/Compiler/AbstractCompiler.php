<?php

namespace Bankiru\Seo\Destination\Compiler;

use Bankiru\Seo\CompiledLink;
use Bankiru\Seo\Context\ContextExtractor;
use Bankiru\Seo\Destination\CompilableLinkInterface;
use Bankiru\Seo\Destination\DestinationCompiler;
use Bankiru\Seo\Destination\DestinationNormalizer;
use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\Exception\DestinationException;

abstract class AbstractCompiler implements DestinationCompiler
{
    /** @var ContextExtractor */
    private $extractor;
    /** @var \Twig_Environment */
    private $twig;
    /** @var DestinationNormalizer */
    private $normalizer;

    /**
     * AbstractCompiler constructor.
     *
     * @param DestinationNormalizer $normalizer
     */
    public function __construct(DestinationNormalizer $normalizer)
    {
        $this->extractor  = new ContextExtractor();
        $this->twig       = new \Twig_Environment(new \Twig_Loader_Array([]));
        $this->normalizer = $normalizer;
    }

    /** {@inheritdoc} */
    final public function compile(DestinationInterface $destination, $title = null, array $attributes = [])
    {
        $href  = $this->getHrefTemplate($destination->getRoute());
        $items = array_map([$this, 'normalize'], iterator_to_array($destination));

        try {
            return new CompiledLink(
                $href->compile($items),
                $this->twig->createTemplate($title)->render($this->extractor->extractContext($destination)),
                $attributes
            );
        } catch (\Twig_Error $error) {
            throw new DestinationException(
                'Error rendering destination title: ' . $error->getMessage(),
                $error->getCode(),
                $error
            );
        }
    }

    /**
     * Allows replace twig via DI
     *
     * @param \Twig_Environment $twig
     */
    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $route
     *
     * @return CompilableLinkInterface
     */
    abstract protected function getHrefTemplate($route);

    private function normalize($item)
    {
        if (!$this->normalizer->supports($item)) {
            throw DestinationException::normalizationFailed($item);
        }

        return $this->normalizer->normalize($item);
    }
}
