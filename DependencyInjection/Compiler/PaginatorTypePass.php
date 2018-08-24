<?php

namespace Nadia\Bundle\PaginatorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class PaginatorTypePass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $paginatorTypeLoaderService;

    /**
     * @var string
     */
    private $paginatorTypeTag;

    /**
     * PaginatorTypePass constructor.
     *
     * @param string $paginatorTypeLoaderService
     * @param string $paginatorTypeTag
     */
    public function __construct($paginatorTypeLoaderService = 'nadia_paginator.type_loader', $paginatorTypeTag = 'nadia_paginator.type')
    {
        $this->paginatorTypeLoaderService = $paginatorTypeLoaderService;
        $this->paginatorTypeTag = $paginatorTypeTag;
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->paginatorTypeLoaderService)) {
            return;
        }

        $definition = $container->getDefinition($this->paginatorTypeLoaderService);

        $definition->replaceArgument(0, $this->processPaginatorTypes($container));
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return Reference
     */
    private function processPaginatorTypes(ContainerBuilder $container)
    {
        $servicesMap = array();

        foreach ($container->findTaggedServiceIds($this->paginatorTypeTag, true) as $serviceId => $tag) {
            $serviceDefinition = $container->getDefinition($serviceId);
            $servicesMap[$serviceDefinition->getClass()] = new Reference($serviceId);
        }

        return ServiceLocatorTagPass::register($container, $servicesMap);
    }
}
