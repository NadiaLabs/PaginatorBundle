<?php

namespace Nadia\Bundle\PaginatorBundle\DependencyInjection;

use Nadia\Bundle\PaginatorBundle\Input\InputKeys;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * PaginatorBundle configuration structure.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nadia_paginator');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('default_options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('input_key_class')->defaultValue(InputKeys::class)->end()
                        ->scalarNode('default_limit')->defaultValue(10)->end()
                        ->scalarNode('default_page_range')->defaultValue(8)->end()
                        ->scalarNode('session_enabled')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('pages')->defaultValue('@NadiaPaginator/templates/bootstrap4/pages.html.twig')->end()
                        ->scalarNode('searches')->defaultValue('@NadiaPaginator/templates/bootstrap4/searches.html.twig')->end()
                        ->scalarNode('filters')->defaultValue('@NadiaPaginator/templates/bootstrap4/filters.html.twig')->end()
                        ->scalarNode('sort_form')->defaultValue('@NadiaPaginator/templates/bootstrap4/sort_form.html.twig')->end()
                        ->scalarNode('sort_link')->defaultValue('@NadiaPaginator/templates/bootstrap4/sort_link.html.twig')->end()
                        ->scalarNode('limit_form')->defaultValue('@NadiaPaginator/templates/bootstrap4/limit_form.html.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
