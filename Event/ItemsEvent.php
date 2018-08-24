<?php

namespace Nadia\Bundle\PaginatorBundle\Event;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\Input;
use Symfony\Component\EventDispatcher\Event;

/**
 * The Event class when fetching target's paginated items
 */
class ItemsEvent extends Event
{
    /**
     * A target for paginating
     *
     * @var mixed
     */
    public $target;

    /**
     * The paginated items
     *
     * @var mixed
     */
    public $items;

    /**
     * The paginated items count
     *
     * @var int
     */
    public $count;

    /**
     * The PaginatorBuilder instance
     *
     * @var PaginatorBuilder
     */
    private $builder;

    /**
     * @var Input
     */
    private $input;

    /**
     * ItemsEvent constructor.
     *
     * @param PaginatorBuilder $builder
     * @param Input            $input
     */
    public function __construct(PaginatorBuilder $builder, Input $input)
    {
        $this->builder = $builder;
        $this->input = $input;
    }

    /**
     * @return PaginatorBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @return Input
     */
    public function getInput()
    {
        return $this->input;
    }
}
