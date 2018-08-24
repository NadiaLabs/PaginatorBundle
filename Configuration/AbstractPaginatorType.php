<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractPaginatorType
 */
abstract class AbstractPaginatorType implements PaginatorTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(PaginatorBuilder $builder, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
