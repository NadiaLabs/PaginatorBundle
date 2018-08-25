<?php

namespace Nadia\Bundle\PaginatorBundle;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Event\BeforeEvent;
use Nadia\Bundle\PaginatorBundle\Event\InputEvent;
use Nadia\Bundle\PaginatorBundle\Event\ItemsEvent;
use Nadia\Bundle\PaginatorBundle\Event\PaginationEvent;
use Nadia\Bundle\PaginatorBundle\Input\Input;
use Nadia\Bundle\PaginatorBundle\Pagination\PaginationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class Paginator
 */
class Paginator
{
    /**
     * @var PaginatorBuilder
     */
    private $builder;

    /**
     * @var array
     */
    private $options;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var Input
     */
    private $input;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Paginator constructor.
     *
     * @param PaginatorBuilder         $builder         PaginatorBuilder instance
     * @param array                    $options         PaginatorType options
     * @param FormInterface            $form            FormInterface instance
     * @param Input                    $input           Input instance
     * @param EventDispatcherInterface $eventDispatcher EventDispatcher instance
     */
    public function __construct(
        PaginatorBuilder $builder,
        array $options,
        FormInterface $form,
        Input $input,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->builder = $builder;
        $this->options = $options;
        $this->form = $form;
        $this->input = $input;
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
        $beforeEvent = new BeforeEvent($this->eventDispatcher);
        $this->eventDispatcher->dispatch('nadia_paginator.before', $beforeEvent);

        if (!is_null($page) && is_numeric($page)) {
            $this->input->setPage((int) $page);
        }
        if (!is_null($pageSize) && is_numeric($pageSize)) {
            $this->input->setPageSize((int) $pageSize);
        }

        $itemsEvent = new ItemsEvent($this->builder, $this->input);
        $itemsEvent->target =& $target;
        $this->eventDispatcher->dispatch('nadia_paginator.items', $itemsEvent);

        $paginationEvent = new PaginationEvent();
        $this->eventDispatcher->dispatch('nadia_paginator.pagination', $paginationEvent);

        $pagination = $paginationEvent->getPagination();

        $pagination->setBuilder($this->builder);
        $pagination->setOptions($this->options);
        $pagination->setCount($itemsEvent->count);
        $pagination->setItems($itemsEvent->items);
        $pagination->setForm($this->form);
        $pagination->setInput($this->input);

        return $pagination;
    }
}
