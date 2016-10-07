<?php

namespace Bankiru\Seo\Entity;

use Bankiru\Seo\DestinationInterface;
use Bankiru\Seo\Page\SeoPageInterface;
use Bankiru\Seo\SourceInterface;
use Bankiru\Seo\ConditionInterface;
use Bankiru\Seo\TargetDefinitionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class AbstractTargetDefinition implements TargetDefinitionInterface
{
    /**
     * @var Collection|ConditionInterface[]
     */
    protected $conditions;

    /**
     * @var SeoPageInterface
     */
    protected $page;

    /**
     * AbstractTargetDefinition constructor.
     */
    public function __construct()
    {
        $this->conditions = new ArrayCollection();
    }

    /** {@inheritdoc} */
    public function match(DestinationInterface $destination)
    {
        if ($destination->getRoute() !== $this->getRoute()) {
            return null;
        }

        $result = 0;
        foreach ($this->conditions as $code => $condition) {
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

    /** {@inheritdoc} */
    public function getSeoPage()
    {
        return $this->page;
    }

    /**
     * @param SourceInterface[] $sources
     *
     * @return int
     */
    public function count(array $sources)
    {
        $count       = 1;
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

    /**
     * @param SeoPageInterface|null $page
     */
    public function setPage(SeoPageInterface $page = null)
    {
        $this->page = $page;
    }
}
