<?php

namespace Bankiru\Seo;

interface SourceFiller
{
    /**
     * Returns source or filler codes which it depends on
     *
     * @return string[]
     */
    public function getDependencies();

    /**
     * Infer filling value using dependency values
     *
     * @param mixed[] $dependencies values indexed by dependency code
     *
     * @return mixed inferred value
     */
    public function infer(array $dependencies);
}
