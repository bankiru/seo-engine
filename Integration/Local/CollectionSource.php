<?php

namespace Bankiru\Seo\Integration\Local;

use Bankiru\Seo\ConditionInterface;
use Bankiru\Seo\SourceInterface;
use Doctrine\Common\Collections\Collection;

final class CollectionSource implements \IteratorAggregate, SourceInterface
{
    /** @var  Collection */
    private $collection;
    /** @var  ConditionInterface[] */
    private $conditions = [];

    /**
     * CollectionSource constructor.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /** {@inheritdoc} */
    public function count()
    {
        return iterator_count($this->createFilterIterator());
    }

    /** {@inheritdoc} */
    public function withCondition(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;
    }

    /** {@inheritdoc} */
    public function getIterator()
    {
        return $this->createFilterIterator();
    }

    /**
     * @return \CallbackFilterIterator
     */
    private function createFilterIterator()
    {
        return new \CallbackFilterIterator(
            new \IteratorIterator($this->collection->getIterator()),
            function ($element) {
                foreach ($this->conditions as $condition) {
                    if (!$condition->match($element)) {
                        return false;
                    }
                }

                return true;
            }
        );
    }
}
