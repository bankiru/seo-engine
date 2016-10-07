<?php

namespace Bankiru\Seo;

interface ConditionInterface
{
    /**
     * @param TargetDefinitionInterface $target
     * @param string                    $code
     */
    public function attach(TargetDefinitionInterface $target, $code);

    /**
     * @param mixed $object
     *
     * @return float
     */
    public function match($object);
}
