<?php

namespace Bankiru\Seo\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

final class BankiruSeoEngineExtension extends Extension
{
    /** {@inheritdoc} */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('seo.yml');

        if (isset($config['target_repository'])) {
            $container->setAlias('bankiru.seo.target_repository', $config['target_repository']);
        }
        if (isset($config['page_repository'])) {
            $container->setAlias('bankiru.seo.page_repository', $config['page_repository']);
        }
    }

    public function getAlias()
    {
        return 'bankiru_seo';
    }
}
