<?php

namespace Bankiru\Seo\Integration\Local;

use Bankiru\Seo\ConditionInterface;
use Bankiru\Seo\TargetDefinitionInterface;

final class ExactCondition implements ConditionInterface
{
    /** @var mixed */
    private $value;
    /** @var bool */
    private $strict = true;

    /**
     * ExactCondition constructor.
     *
     * @param mixed $value
     * @param bool  $strict
     */
    public function __construct($value, $strict = true)
    {
        $this->value  = $value;
        $this->strict = $strict;
    }

    /** {@inheritdoc} */
    public function attach(TargetDefinitionInterface $target, $code)
    {
        $target->setCondition($code, $this);
    }

    /** {@inheritdoc} */
    public function match($object)
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        return $this->doMatch($object) ? 1 : null;
    }

    /**
     * @param $object
     *
     * @return bool
     */
    private function doMatch($object)
    {
        return $this->strict ? $object === $this->value : $object == $this->value;
    }
}
