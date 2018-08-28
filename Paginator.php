<?php

namespace Nadia\Bundle\PaginatorBundle;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Event\BeforeEvent;
use Nadia\Bundle\PaginatorBundle\Event\ItemsEvent;
use Nadia\Bundle\PaginatorBundle\Event\PaginationEvent;
use Nadia\Bundle\PaginatorBundle\Pagination\PaginationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Paginator
{
    /**
     * @var PaginatorBuilder
     */
    private $builder;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Paginator constructor.
     *
     * @param PaginatorBuilder         $builder
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(PaginatorBuilder $builder,EventDispatcherInterface $eventDispatcher)
    {
        $this->builder = $builder;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Do the paginate process and generate Pagination instance
     *
     * @param mixed    $target Paginating target, retrieve data from this target instance
     * @param int|null $page
     * @param int|null $pageSize
     *
     * @return PaginationInterface
     */
    public function paginate($target, $page = null, $pageSize = null)
    {
        $beforeEvent = new BeforeEvent($this->builder, $this->eventDispatcher);
        $this->eventDispatcher->dispatch('nadia_paginator.before', $beforeEvent);

        if (!is_null($page) && is_numeric($page)) {
            $beforeEvent->input->setPage((int) $page);
        }
        if (!is_null($pageSize) && is_numeric($pageSize)) {
            $beforeEvent->input->setPageSize((int) $pageSize);
        }

        $itemsEvent = new ItemsEvent($this->builder, $beforeEvent->input);
        $itemsEvent->target =& $target;
        $this->eventDispatcher->dispatch('nadia_paginator.items', $itemsEvent);

        $paginationEvent = new PaginationEvent($this->builder);
        $this->eventDispatcher->dispatch('nadia_paginator.pagination', $paginationEvent);

        $pagination = $paginationEvent->pagination;

        $pagination->setCount($itemsEvent->count);
        $pagination->setItems($itemsEvent->items);
        $pagination->setForm($beforeEvent->form);
        $pagination->setInput($beforeEvent->input);

        return $pagination;
    }
}
