<?php

namespace Nadia\Bundle\PaginatorBundle\Event;

use Nadia\Bundle\PaginatorBundle\Pagination\PaginationInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Specific Event class for paginator
 */
class PaginationEvent extends Event
{
    /**
     * A target being paginated
     *
     * @var mixed
     */
    public $target;

    /**
     * List of options
     *
     * @var array
     */
    public $options;

    /**
     * @var PaginationInterface
     */
    private $pagination;

    /**
     * @param PaginationInterface $pagination
     */
    public function setPagination(PaginationInterface $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @return PaginationInterface
     */
    public function getPagination()
    {
        return $this->pagination;
    }
}
