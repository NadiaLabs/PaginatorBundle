<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base Filter configuration class
 */
class Filter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(
        FilterBuilder $builder,
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
