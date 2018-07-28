<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Sort
 */
class Sort implements SortInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(
        SortBuilder $builder,
        QueryProcessorCollection $queryProcessors,
        array $options = []
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
