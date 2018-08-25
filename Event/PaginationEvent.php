<?php

namespace Nadia\Bundle\PaginatorBundle\Event;

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
    private $pagination;

    /**
     * @param Pagination $pagination
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @return Pagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }
}
