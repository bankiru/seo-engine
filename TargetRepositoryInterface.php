<?php

namespace Bankiru\Seo;

interface TargetRepositoryInterface
{
    /**
     * @param string $route
     *
     * @return TargetDefinitionInterface[]
     */
    public function findByRoute($route);
}
