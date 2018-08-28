<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * An interface for PaginatorType configuration class
 */
interface PaginatorTypeInterface
{
    /**
     * Build search configuration
     *
     * @param SearchBuilder $builder
     * @param array         $options The options for this PaginatorType
     *
     * @return void
     */
    public function buildSearch(SearchBuilder $builder, array &$options);

    /**
     * Build filter configuration
     *
     * @param FilterBuilder $builder
     * @param array         $options The options for this PaginatorType
     *
     * @return void
     */
    public function buildFilter(FilterBuilder $builder, array &$options);

    /**
     * Build sort configuration
     *
     * @param SortBuilder $builder
     * @param array       $options The options for this PaginatorType
     *
     * @return void
     */
    public function buildSort(SortBuilder $builder, array &$options);

    /**
     * Build page size configuration
     *
     * @param PageSizeBuilder $builder
     * @param array           $options The options for this PaginatorType
     *
     * @return void
     */
    public function buildPageSize(PageSizeBuilder $builder, array &$options);

    /**
     * Configure paginator form options
     *
     * @param array $options The options for this PaginatorType
     *
     * @return array
     */
    public function getFormOptions(array &$options);

    /**
     * Configures the options for this PaginatorType configuration.
     *
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
