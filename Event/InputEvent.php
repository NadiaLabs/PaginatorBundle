<?php

namespace Nadia\Bundle\PaginatorBundle\Event;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Symfony\Component\EventDispatcher\Event;

/**
 * The Event class when build form & input instances
 */
class InputEvent extends Event
{
    /**
     * @var array
     */
    public $options;

    /**
     * @var PaginatorBuilder
     */
    private $builder;

    /**
     * InputEvent constructor.
     *
     * @param PaginatorBuilder $builder
     */
    public function __construct(PaginatorBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return PaginatorBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }
}
