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
    private $paginatorTypeContainerService;

    /**
     * @var string
     */
    private $paginatorTypeTag;

    /**
     * PaginatorTypePass constructor.
     *
     * @param string $paginatorTypeContainerService
     * @param string $paginatorTypeTag
     */
    public function __construct($paginatorTypeContainerService = 'nadia_paginator.type_container', $paginatorTypeTag = 'nadia_paginator.type')
    {
        $this->paginatorTypeContainerService = $paginatorTypeContainerService;
        $this->paginatorTypeTag = $paginatorTypeTag;
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->paginatorTypeContainerService)) {
            return;
        }

        $definition = $container->getDefinition($this->paginatorTypeContainerService);

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
