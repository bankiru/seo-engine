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
     * Returns the map code -> source_id where code is the target route placeholder code and the
     * source_id is the identifier of the SourceInterface stored in SourceRegistry
     *
     * @return string[]
     */
    public function getSources();

    /**
     * Returns the template to form the link title. Implementation of templating
     * mechanism is defined by external services
     *
     * @return string
     */
    public function getTitleTemplate();

    /**
     * @return SourceFiller[]
     */
    public function getFillers();
}
