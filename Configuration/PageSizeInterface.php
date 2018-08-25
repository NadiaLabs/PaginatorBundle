<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * An interface for PageSize configuration class
 */
interface PageSizeInterface
{
    /**
     * Build PageSize configuration
     *
     * @param PageSizeBuilder $builder
     *
     * @return void
     */
    public function build(PageSizeBuilder $builder);
}
