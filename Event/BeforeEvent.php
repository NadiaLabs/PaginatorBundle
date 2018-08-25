<?php

namespace Nadia\Bundle\PaginatorBundle\Event;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\Input;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

/**
 * The Event class for preparing paginating processes
 */
class BeforeEvent extends Event
{
    /**
     * @var FormInterface
     */
    public $form;

    /**
     * @var Input
     */
    public $input;

    /**
     * @var PaginatorBuilder
     */
    private $builder;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * BeforeEvent constructor.
     *
     * @param PaginatorBuilder         $builder
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(PaginatorBuilder $builder, EventDispatcherInterface $eventDispatcher)
    {
        $this->builder = $builder;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return PaginatorBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}
