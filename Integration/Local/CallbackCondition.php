<?php

namespace Bankiru\Seo\Integration\Local;

use Bankiru\Seo\ConditionInterface;
use Bankiru\Seo\TargetDefinitionInterface;

final class CallbackCondition implements ConditionInterface
{
    /** @var callable */
    private $callback;

    /**
     * CallbackCondition constructor.
     *
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /** {@inheritdoc} */
    public function attach(TargetDefinitionInterface $target, $code)
    {
        $target->setCondition($code, $this);
    }

    /** {@inheritdoc} */
    public function match($object)
    {
        return call_user_func($this->callback, $object) ? 1 : null;
    }
}
