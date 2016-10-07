<?php

namespace Bankiru\Seo\Integration\Local;

use Bankiru\Seo\SourceFiller;

final class CallbackFiller implements SourceFiller
{
    /** @var string[] */
    private $dependencies = [];
    /** @var callable */
    private $closure;

    /**
     * CallbackFiller constructor.
     *
     * @param string[] $dependencies
     * @param callable $closure
     */
    public function __construct(array $dependencies, callable $closure)
    {
        $this->dependencies = $dependencies;
        $this->closure      = $closure;
    }

    /** {@inheritdoc} */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /** {@inheritdoc} */
    public function infer(array $dependencies)
    {
        return call_user_func(
            $this->closure,
            array_intersect_key($dependencies, array_flip($this->getDependencies()))
        );
    }
}
