<?php

namespace Bankiru\Seo\Entity;

use Bankiru\Seo\Exception\ConditionException;
use Bankiru\Seo\ConditionInterface;
use Bankiru\Seo\TargetDefinitionInterface;

abstract class AbstractCondition implements ConditionInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var TargetDefinitionInterface
     */
    protected $targetDefinition;

    /** {@inheritdoc} */
    public function attach(TargetDefinitionInterface $target, $code)
    {
        $this->code             = $code;
        $this->targetDefinition = $target;
        $this->targetDefinition->setCondition($this->code, $this);
    }

    /** {@inheritdoc} */
    public function match($object)
    {
        if (!$this->supports($object)) {
            throw ConditionException::unsupportedArgumentToMatch($this, $object);
        }

        return $this->doMatch($object);
    }

    /**
     * Checks whether the object is valid argument to perform matching
     *
     * @param mixed $object
     *
     * @return bool
     */
    abstract protected function supports($object);

    /**
     * Verify that object matches according to condition configuration
     *
     * @param $object
     *
     * @return int|null
     */
    abstract protected function doMatch($object);
}
