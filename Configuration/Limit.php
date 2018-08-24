<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * Base Limit configuration class
 */
class Limit implements LimitInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(LimitBuilder $builder)
    {
        $pageSizes = array(5 => 5, 10 => 10, 20 => 20, 25 => 25, 50 => 50, 100 => 100, 200 => 200);

        $builder->add($pageSizes);
    }
}
