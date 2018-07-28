<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * An interface for Filter configuration class
 */
interface FilterInterface
{
    /**
     * Build Filter configuration
     *
     * @param FilterBuilder            $builder
     * @param QueryProcessorCollection $queryProcessors
     * @param array                    $options
     *
     * @return void
     */
    public function build(
        FilterBuilder $builder,
        QueryProcessorCollection $queryProcessors,
        array $options
    );

    /**
     * Configures the options for this Filter configuration.
     *
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
