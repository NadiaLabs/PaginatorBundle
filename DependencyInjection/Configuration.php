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
                        ->scalarNode('default_limit')->defaultValue(10)->end()
                        ->scalarNode('session_enabled')->defaultTrue()->end()
                        ->scalarNode('input_key_class')->defaultValue(InputKeys::class)->end()
                    ->end()
                ->end()
                ->arrayNode('template')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('pagination')->defaultValue('@NadiaPaginator/templates/bootstrap4/pagination.html.twig')->end()
                        ->booleanNode('search_form')->defaultValue('@NadiaPaginator/templates/bootstrap4/search_form.html.twig')->end()
                        ->booleanNode('filter_form')->defaultValue('@NadiaPaginator/templates/bootstrap4/filter_form.html.twig')->end()
                        ->booleanNode('sort_form')->defaultValue('@NadiaPaginator/templates/bootstrap4/sort_form.html.twig')->end()
                        ->booleanNode('sort_ink')->defaultValue('@NadiaPaginator/templates/bootstrap4/sort_ink.html.twig')->end()
                        ->booleanNode('limit_form')->defaultValue('@NadiaPaginator/templates/bootstrap4/limit_form.html.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
