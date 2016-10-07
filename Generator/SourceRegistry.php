<?php

namespace Bankiru\Seo\Generator;

use Bankiru\Seo\SourceInterface;

final class SourceRegistry
{
    /** @var SourceInterface[] */
    private $sources = [];

    /**
     * @return SourceInterface[]
     */
    public function all()
    {
        return $this->sources;
    }

    /**
     * @param string          $key
     * @param SourceInterface $source
     */
    public function add($key, SourceInterface $source)
    {
        $this->sources[$key] = $source;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->sources);
    }

    /**
     * @param string $key
     *
     * @return SourceInterface
     * @throws \OutOfBoundsException
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new \OutOfBoundsException('Source not found: '.$key);
        }

        return $this->sources[$key];
    }
}

