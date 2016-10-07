<?php

namespace Bankiru\Seo\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class SeoSourcePass implements CompilerPassInterface
{
    /** {@inheritdoc} */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('bankiru.seo.source_registry')) {
            return;
        }

        $registry = $container->getDefinition('bankiru.seo.source_registry');
        $services = $container->findTaggedServiceIds('seo_source');

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                if (!array_key_exists('code', $attributes)) {
                    throw new \RuntimeException(sprintf('No code configured for %s' . $id));
                }
                $code = $attributes['code'];

                $registry->addMethodCall('add', [$code, new Reference($id)]);
            }
        }
    }
}
