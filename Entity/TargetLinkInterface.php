<?php

namespace Bankiru\Seo\Entity;

use Bankiru\Seo\SourceFiller;
use Bankiru\Seo\TargetDefinitionInterface;

interface TargetLinkInterface
{
    /**
     * @return TargetDefinitionInterface
     */
    public function getTarget();

    /**
     * @return string[]
     */
    public function getSources();

    /**
     * @return string
     */
    public function getTitleTemplate();

    /**
     * @return SourceFiller[]
     */
    public function getFillers();
}
