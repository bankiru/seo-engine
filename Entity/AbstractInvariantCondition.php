<?php

namespace Bankiru\Seo\Entity;

abstract class AbstractInvariantCondition extends AbstractCondition
{
    /** @var  bool */
    private $state = false;

    /**
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param boolean $state
     */
    public function setState($state)
    {
        $this->state = (bool)$state;
    }

    /**
     * @param mixed $object
     *
     * @return boolean
     */
    abstract protected function getInvariant($object);

    /** {@inheritdoc} */
    final protected function doMatch($object)
    {
        return $this->state === $this->getInvariant($object) ? 1 : null;
    }
}
