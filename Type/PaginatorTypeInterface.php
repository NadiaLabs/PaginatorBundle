<?php

namespace NadiaProject\Bundle\PaginatorBundle\Type;

use NadiaProject\Bundle\PaginatorBundle\Builder\PaginatorBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface PaginatorTypeInterface
{
    public function buildPaginator(PaginatorBuilder $builder, array $options = []);
    public function configureOptions(OptionsResolver $resolver);
}