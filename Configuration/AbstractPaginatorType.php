<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractPaginatorType
 */
abstract class AbstractPaginatorType implements PaginatorTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildSearch(SearchBuilder $builder, array &$options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildFilter(FilterBuilder $builder, array &$options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildSort(SortBuilder $builder, array &$options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildPageSize(PageSizeBuilder $builder, array &$options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFormOptions(array &$options)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
