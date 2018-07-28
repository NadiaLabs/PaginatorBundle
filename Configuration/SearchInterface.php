<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * An interface for Search configuration class
 */
interface SearchInterface
{
    /**
     * Build Search configuration
     *
     * @param SearchBuilder            $builder
     * @param QueryProcessorCollection $queryProcessors
     * @param array                    $options
     *
     * @return void
     */
    public function build(
        SearchBuilder $builder,
        QueryProcessorCollection $queryProcessors,
        array $options
    );

    /**
     * Configures the options for this Search configuration.
     *
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
