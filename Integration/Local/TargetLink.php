<?php

namespace Bankiru\Seo\Integration\Local;

use Bankiru\Seo\Entity\TargetLinkInterface;
use Bankiru\Seo\SourceFiller;
use Bankiru\Seo\TargetDefinitionInterface;

final class TargetLink implements TargetLinkInterface
{
    /** @var  TargetDefinitionInterface */
    private $target;
    /** @var string[] */
    private $sources = [];
    /** @var  string */
    private $template;
    /** @var SourceFiller[] */
    private $fillers = [];

    /**
     * TargetLink constructor.
     *
     * @param TargetDefinitionInterface $target
     * @param string[]                  $sources
     * @param string                    $template
     * @param SourceFiller[]            $fillers
     */
    public function __construct(TargetDefinitionInterface $target, $template, array $sources, array $fillers = [])
    {
        $this->target   = $target;
        $this->sources  = $sources;
        $this->template = $template;
        $this->fillers  = $fillers;
    }

    /** {@inheritdoc} */
    public function getTarget()
    {
        return $this->target;
    }

    /** {@inheritdoc} */
    public function getSources()
    {
        return $this->sources;
    }

    /** {@inheritdoc} */
    public function getTitleTemplate()
    {
        return $this->template;
    }

    /** {@inheritdoc} */
    public function getFillers()
    {
        return $this->fillers;
    }
}
