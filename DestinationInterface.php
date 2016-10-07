<?php

namespace Bankiru\Seo;

interface DestinationInterface extends \Traversable
{
    /**
     * Return single value by key
     *
     * @param string $key
     */
    public function resolve($key);

    /**
     * @return string
     */
    public function getRoute();
}
