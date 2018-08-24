<?php

namespace Nadia\Bundle\PaginatorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class NadiaPaginatorExtension
 */
class NadiaPaginatorExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.yml');

        $paginatorFactoryDef = $container->getDefinition('nadia_paginator.paginator_factory');
        $defaultOptions = array(
            'inputKeysClass'    => $config['default_options']['input_key_class'],
            'defaultLimit'      => $config['default_options']['default_limit'],
            'defaultPageRange'  => $config['default_options']['default_page_range'],
            'sessionEnabled'    => $config['default_options']['session_enabled'],
            'pagesTemplate'     => $config['templates']['pages'],
            'searchesTemplate'  => $config['templates']['searches'],
            'filtersTemplate'   => $config['templates']['filters'],
            'sortFormTemplate'  => $config['templates']['sort_form'],
            'sortLinkTemplate'  => $config['templates']['sort_link'],
            'limitFormTemplate' => $config['templates']['limit_form'],
        );

        $paginatorFactoryDef->replaceArgument(2, $defaultOptions);
    }
}
