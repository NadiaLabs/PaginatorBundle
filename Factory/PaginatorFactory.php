<?php

namespace Nadia\Bundle\PaginatorBundle\Factory;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface;
use Nadia\Bundle\PaginatorBundle\Paginator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaginatorFactory
 */
class PaginatorFactory
{
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
     * @param EventDispatcherInterface $eventDispatcher
     * @param array                    $defaultOptions
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, array $defaultOptions = array())
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * @param PaginatorTypeInterface $type
     * @param array                  $options {
     *     @var string $inputKeysClass    @see \Nadia\Bundle\PaginatorBundle\Input\InputKeys
     *     @var int    $defaultPageSize   Default page size
     *     @var int    $defaultPageRange  Default page range (control page link amounts)
     *     @var bool   $sessionEnabled    Enable session support, store input data in session
     *     @var string $pagesTemplate     Template for rendering pages
     *     @var string $searchesTemplate  Template for rendering searches block
     *     @var string $filtersTemplate   Template for rendering filters block
     *     @var string $sortsTemplate     Template for rendering sort selection block
     *     @var string $sortLinkTemplate  Template for rendering sort link
     *     @var string $pageSizesTemplate Template for rendering page size selection block
     * }
     *
     * @return Paginator
     */
    public function create(PaginatorTypeInterface $type, array $options = array())
    {
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
