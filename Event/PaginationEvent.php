<?php

namespace Nadia\Bundle\PaginatorBundle\Event;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Pagination\Pagination;
use Symfony\Component\EventDispatcher\Event;

/**
 * Specific Event class for paginator
 */
class PaginationEvent extends Event
{
    /**
     * @var Pagination
     */
    public $pagination;

    /**
     * @var PaginatorBuilder
     */
    private $builder;

    /**
     * PaginationEvent constructor.
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
