<?php
namespace Bankiru\Seo;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class Destination implements \IteratorAggregate, DestinationInterface
{
    /** @var string */
    private $route;
    /** @var Collection */
    private $items;

    /**
     * @param string $route
     * @param array  $items
     */
    public function __construct($route, array $items)
    {
        $this->route = $route;
        $this->items = new ArrayCollection($items);
    }

    public function getIterator()
    {
        return $this->items->getIterator();
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /** {@inheritdoc} */
    public function resolve($key)
    {
        $value = $this->items->get($key);
        if (is_callable($value)) {
            $value = $value();
        }

        return $value;
    }
}
