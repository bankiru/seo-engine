<?php

namespace Bankiru\Seo\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /** {@inheritdoc} */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root    = $builder->root('bankiru_seo');

        $root->children()->scalarNode('target_repository')->defaultNull();
        $root->children()->scalarNode('page_repository')->defaultNull();

        return $builder;
    }
}
