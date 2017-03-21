<?php

namespace Bankiru\Seo\Entity;

use Bankiru\Seo\ConditionInterface;
use Bankiru\Seo\Exception\ConditionException;
use Bankiru\Seo\TargetDefinitionInterface;

abstract class AbstractCondition implements ConditionInterface
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var TargetDefinitionInterface
     */
    protected $target;

    /**
     * AbstractCondition constructor.
     *
     * @param string                    $code
     * @param TargetDefinitionInterface $target
     */
    public function __construct($code, TargetDefinitionInterface $target)
    {
        $this->code   = $code;
        $this->target = $target;
    }

    /** {@inheritdoc} */
    public function attach(TargetDefinitionInterface $target, $code)
    {
        $this->code   = $code;
        $this->target = $target;
        $this->target->setCondition($this->code, $this);
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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return TargetDefinitionInterface
     */
    public function getTarget()
    {
        return $this->target;
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
