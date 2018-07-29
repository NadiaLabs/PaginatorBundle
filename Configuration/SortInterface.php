<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * An interface for Sort configuration class
 */
interface SortInterface
{
    /**
     * Build Sort configuration
     *
     * @param SortBuilder $builder
     *
     * @return void
     */
    public function build(SortBuilder $builder);
}
