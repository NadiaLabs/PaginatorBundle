<?php

namespace Nadia\Bundle\PaginatorBundle;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface;
use Nadia\Bundle\PaginatorBundle\Input\QueryParameterDefinition;
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

    /**
     * {@inheritdoc}
     */
    public function defaultConfigureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'queryParams' => new QueryParameterDefinition(),
            'defaultLimit' => 10,
            'sessionKey' => 'nadia.paginator.session.' . hash('md5', get_class($this)),
            'template' => '@NadiaPaginator/template-bootstrap4.html.twig',
        ]);
    }
}
