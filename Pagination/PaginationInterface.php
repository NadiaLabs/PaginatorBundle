<?php

namespace Nadia\Bundle\PaginatorBundle\Pagination;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\Input;

/**
 * Interface PaginationInterface
 */
interface PaginationInterface
{
    /**
     * @return PaginatorBuilder
     */
    public function getBuilder();

    /**
     * @param PaginatorBuilder $builder
     *
     * @return $this
     */
    public function setBuilder(PaginatorBuilder $builder);

    /**
     * @return array PaginatorType options
     */
    public function getOptions();

    /**
     * @param array $options PaginatorType options
     *
     * @return $this
     */
    public function setOptions(array $options);

    /**
     * @return Input
     */
    public function getInput();

    /**
     * @param Input $input
     *
     * @return $this
     */
    public function setInput(Input $input);

    /**
     * @return int
     */
    public function getCount();

    /**
     * @param int $count
     *
     * @return $this
     */
    public function setCount($count);

    /**
     * @return mixed
     */
    public function getItems();

    /**
     * @param mixed $items
     *
     * @return $this
     */
    public function setItems($items);
}
