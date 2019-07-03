<?php

namespace Nadia\Bundle\PaginatorBundle\Factory;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface;
use Nadia\Bundle\PaginatorBundle\Paginator;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaginatorFactory
 */
class PaginatorFactory
{
    /**
     * @var ContainerInterface
     */
    private $paginatorTypeContainer;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var array
     */
    private $defaultOptions;

    /**
     * PaginatorFactory constructor.
     *
     * @param ContainerInterface $paginatorTypeContainer
     * @param EventDispatcherInterface $eventDispatcher
     * @param array $defaultOptions
     */
    public function __construct(ContainerInterface $paginatorTypeContainer,
                                EventDispatcherInterface $eventDispatcher,
                                array $defaultOptions = array())
    {
        $this->paginatorTypeContainer = $paginatorTypeContainer;
        $this->eventDispatcher = $eventDispatcher;
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * @param PaginatorTypeInterface|string $type    An PaginatorTypeInterface instance or
     *                                               a class name that implement PaginatorTypeInterface or
     *                                               a service id for an PaginatorTypeInterface service
     * @param array                         $options {
     *     @var string           $inputKeysClass
     *     @var int              $defaultPageSize            Default page size
     *     @var int              $defaultPageRange           Default page range (control page link amounts)
     *     @var bool             $sessionEnabled             Enable session support, store input data in session
     *     @var string           $pagesTemplate              Template for rendering pages
     *     @var string           $searchesTemplate           Template for rendering searches block
     *     @var string           $filtersTemplate            Template for rendering filters block
     *     @var string           $sortsTemplate              Template for rendering sort selection block
     *     @var string           $sortLinkTemplate           Template for rendering sort link
     *     @var string           $pageSizesTemplate          Template for rendering page size selection block
     *     @var string|bool|null $translationDomain          The form translation domain (default is null)
     *     @var string|bool|null $paginatorTranslationDomain The paginator translation domain (default is nadia_paginator)
     * }
     *
     * @return Paginator
     *
     * @see \Nadia\Bundle\PaginatorBundle\Input\InputKeys
     */
    public function create($type, array $options = array())
    {
        if (!is_object($type) || !$type instanceof PaginatorTypeInterface) {
            $type = $this->paginatorTypeContainer->get($type);
        }

        $options = $this->resolveOptions($type, $options);
        $builder = new PaginatorBuilder($type, $options);

        return new Paginator($builder, $this->eventDispatcher);
    }

    /**
     * @param PaginatorTypeInterface $type
     * @param array                  $options
     *
     * @return array
     */
    private function resolveOptions(PaginatorTypeInterface $type, array $options)
    {
        $resolver = new OptionsResolver();

        $this->configureDefaultOptions($type, $resolver);

        $type->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    /**
     * @param PaginatorTypeInterface $type
     * @param OptionsResolver        $resolver
     */
    private function configureDefaultOptions(PaginatorTypeInterface $type, OptionsResolver $resolver)
    {
        $defaultOptions = $this->defaultOptions;

        $defaultOptions['sessionKey'] = 'nadia.paginator.session.' . hash('md5', get_class($type));
        $defaultOptions['inputKeys'] = new $defaultOptions['inputKeysClass'];

        $resolver->setDefaults($defaultOptions);
    }
}
