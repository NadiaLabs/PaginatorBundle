<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * An interface for Limit configuration class
 */
interface LimitInterface
{
    /**
     * Build Limit configuration
     *
     * @param LimitBuilder $builder
     *
     * @return void
     */
    public function build(LimitBuilder $builder);
}
