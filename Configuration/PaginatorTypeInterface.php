<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * An interface for PaginatorType configuration class
 */
interface PaginatorTypeInterface
{
    /**
     * Build PaginatorType configuration
     *
     * @param PaginatorBuilder $builder
     * @param array            $options
     *
     * @return void
     */
    public function build(PaginatorBuilder $builder, array $options);

    /**
     * Configures the options for this PaginatorType configuration.
     *
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
