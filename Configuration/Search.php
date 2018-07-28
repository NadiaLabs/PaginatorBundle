<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base Search configuration class
 */
class Search implements SearchInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(
        SearchBuilder $builder,
        QueryProcessorCollection $queryProcessors,
        array $options
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
