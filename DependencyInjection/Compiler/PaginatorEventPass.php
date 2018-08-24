<?php

namespace Nadia\Bundle\PaginatorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class PaginatorEventPass implements CompilerPassInterface
{
    /**
     * Register paginator's events
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // Use main symfony dispatcher
        if (!$container->hasDefinition('event_dispatcher') && !$container->hasAlias('event_dispatcher')) {
            return;
        }

        $pass = new RegisterListenersPass(
            'event_dispatcher',
            'nadia_paginator.listener',
            'nadia_paginator.subscriber'
        );

        $pass->process($container);
    }
}
