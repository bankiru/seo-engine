<?php

namespace Bankiru\Seo\Integration\Local;

use Bankiru\Seo\TargetDefinitionInterface;
use Bankiru\Seo\TargetRepositoryInterface;

final class StaticTargetRepository implements TargetRepositoryInterface
{
    /**
     * @var TargetDefinitionInterface[][]
     */
    private $targets = [];

    public function add(TargetDefinitionInterface $target)
    {
        $this->targets[$target->getRoute()][] = $target;
    }

    /** {@inheritdoc} */
    public function findByRoute($route)
    {
        if (!array_key_exists($route, $this->targets)) {
            return [];
        }

        return $this->targets[$route];
    }
}
