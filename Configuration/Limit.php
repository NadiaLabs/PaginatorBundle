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
        $builder->add([5, 10, 20, 25, 50, 100, 200]);
    }
}
