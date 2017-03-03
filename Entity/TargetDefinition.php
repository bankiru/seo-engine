<?php

namespace Bankiru\Seo\Entity;

use Bankiru\Seo\ConditionInterface;
use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\SourceInterface;
use Bankiru\Seo\TargetDefinitionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TargetDefinition implements TargetDefinitionInterface
{
    /**
     * @var Collection|ConditionInterface[]
     */
    protected $conditions;
    /** @var  string */
    protected $route;

    /**
     * TargetDefinition constructor.
     *
     * @param string $route
     */
    public function __construct($route)
    {
        $this->route      = $route;
        $this->conditions = new ArrayCollection();
    }

    /** {@inheritdoc} */
    public function match(DestinationInterface $destination)
    {
        if ($destination->getRoute() !== $this->getRoute()) {
            return null;
        }

        $conditions = $this->conditions;
        foreach ($destination as $code => $item)
        {
            if (!array_key_exists($code, $this->conditions)) {
                $conditions[$code] = new PermissiveCondition();
            }
        }

        $result = 0;
        foreach ($conditions as $code => $condition) {
            $payload = $destination->resolve($code);
            $score   = $condition->match($payload);
            if ($score === null) {
                return null;
            }
            $result += $score;
        }

        return $result;
    }

    /**
     * Get conditions indexed by code
     *
     * @return ConditionInterface[]
     */
    public function getConditions()
    {
        return $this->conditions->toArray();
    }

    /** {@inheritdoc} */
    public function setCondition($code, ConditionInterface $condition)
    {
        if ($condition instanceof PermissiveCondition) {
            return;
        }

        $this->conditions->set($code, $condition);
    }

    /** {@inheritdoc} */
    public function getCondition($code)
    {
        if ($this->conditions->containsKey($code)) {
            return $this->conditions->get($code);
        }

        return new PermissiveCondition();
    }

    /**
     * @param SourceInterface[] $sources
     *
     * @return int
     */
    public function count(array $sources)
    {
        $count         = 1;
        $uniqueSources = [];
        foreach ($sources as $code => $source) {
            $source->withCondition($this->getCondition($code));
            $uniqueSources[spl_object_hash($source)] = $source;
        }

        foreach ($uniqueSources as $source) {
            $count *= $source->count();
        }

        return $count;
    }

    /** {@inheritdoc} */
    public function getRoute()
    {
        return $this->route;
    }
}
