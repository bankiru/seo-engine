<?php

namespace Bankiru\Seo;

use Bankiru\Seo\DependencyInjection\Compiler\LinkGeneratorPass;
use Bankiru\Seo\DependencyInjection\Compiler\RouterPass;
use Bankiru\Seo\DependencyInjection\Compiler\SeoSourcePass;
use Bankiru\Seo\DependencyInjection\Compiler\SlugNormalizerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BankiruSeoEngineBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SeoSourcePass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new LinkGeneratorPass());
        $container->addCompilerPass(new SlugNormalizerPass());
        $container->addCompilerPass(new RouterPass());
    }
}
