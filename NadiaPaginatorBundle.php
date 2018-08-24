<?php

namespace Nadia\Bundle\PaginatorBundle;

use Nadia\Bundle\PaginatorBundle\DependencyInjection\Compiler\PaginatorEventPass;
use Nadia\Bundle\PaginatorBundle\DependencyInjection\Compiler\PaginatorTypePass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NadiaPaginatorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new PaginatorEventPass(), PassConfig::TYPE_BEFORE_REMOVING);
        $container->addCompilerPass(new PaginatorTypePass(), PassConfig::TYPE_BEFORE_REMOVING);
    }
}
