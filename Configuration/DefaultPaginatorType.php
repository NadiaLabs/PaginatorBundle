<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * Class DefaultPaginatorType
 */
class DefaultPaginatorType extends AbstractPaginatorType
{
    /**
     * {@inheritdoc}
     */
    public function buildPageSize(PageSizeBuilder $builder, array &$options)
    {
        $pageSizes = [5 => 5, 10 => 10, 20 => 20, 50 => 50, 100 => 100, 150 => 150, 200 => 200];

        $builder->add($pageSizes);
    }
}
