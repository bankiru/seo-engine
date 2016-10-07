<?php

namespace Bankiru\Seo\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class LinkGeneratorPass implements CompilerPassInterface
{
    /** {@inheritdoc} */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('bankiru.seo.link_generator')) {
            return;
        }

        $registry = $container->getDefinition('bankiru.seo.link_generator');
        $services = $container->findTaggedServiceIds('seo_link_resolver');

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $registry->addMethodCall('addResolver', [new Reference($id)]);
            }
        }
    }
}
