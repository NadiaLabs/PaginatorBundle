<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * An interface for PaginatorType configuration class
 */
interface PaginatorTypeInterface
{
    /**
     * Build search configuration
     *
     * @param SearchBuilder   $builder
     * @param ArrayCollection $queryProcessors
     * @param array           $options
     *
     * @return void
     */
    public function buildSearch(SearchBuilder $builder, ArrayCollection $queryProcessors, array &$options);

    /**
     * Build filter configuration
     *
     * @param FilterBuilder   $builder
     * @param ArrayCollection $queryProcessors
     * @param array           $options
     *
     * @return void
     */
    public function buildFilter(FilterBuilder $builder, ArrayCollection $queryProcessors, array &$options);

    /**
     * Build sort configuration
     *
     * @param SortBuilder $builder
     * @param array       $options
     *
     * @return void
     */
    public function buildSort(SortBuilder $builder, array &$options);

    /**
     * Build page size configuration
     *
     * @param PageSizeBuilder $builder
     * @param array           $options
     *
     * @return void
     */
    public function buildPageSize(PageSizeBuilder $builder, array &$options);

    /**
     * Configure paginator form options
     *
     * @return array
     */
    public function getFormOptions();

    /**
     * Configures the options for this PaginatorType configuration.
     *
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
