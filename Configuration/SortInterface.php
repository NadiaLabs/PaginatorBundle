<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * An interface for Sort configuration class
 */
interface SortInterface
{
    /**
     * Build Sort configuration
     *
     * @param SortBuilder              $builder
     * @param QueryProcessorCollection $queryProcessors
     * @param array                    $options
     *
     * @return void
     */
    public function build(
        SortBuilder $builder,
        QueryProcessorCollection $queryProcessors,
        array $options
    );

    /**
     * Configures the options for this Sort configuration.
     *
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
