<?php

namespace Bankiru\Seo\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class SlugNormalizerPass implements CompilerPassInterface
{
    /** {@inheritdoc} */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('bankiru.seo.slug.delegated_normalizer')) {
            return;
        }

        $normalizer = $container->getDefinition('bankiru.seo.slug.delegated_normalizer');
        $services   = $container->findTaggedServiceIds('seo_slug_normalizer');

        /** @var Reference[][] $normalizers */
        $normalizers = [];
        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $priority                 = array_key_exists('priority', $attributes) ?
                    (int)$attributes['priority'] :
                    0;
                $normalizers[$priority][] = new Reference($id);
            }
        }

        ksort($normalizers);

        $flat = [];
        foreach ($normalizers as $priority => $pNormalizers) {
            foreach ($pNormalizers as $pNormalizer) {
                $flat[] = $pNormalizer;
            }
        }

        $normalizer->replaceArgument(0, $flat);
    }
}
