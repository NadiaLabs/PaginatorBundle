<?php

namespace Nadia\Bundle\PaginatorBundle\Pagination;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\Input;
use Nadia\Bundle\PaginatorBundle\Input\InputKeys;

/**
 * Class Pagination
 */
abstract class AbstractPagination implements PaginationInterface, \Countable, \Iterator, \ArrayAccess
{
    /**
     * @var PaginatorBuilder
     */
    private $builder;

    /**
     * @var Input
     */
    private $input;

    /**
     * @var int
     */
    private $count;

    /**
     * @var array
     */
    private $items;

    /**
     * AbstractPagination constructor.
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

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->builder->getTypeOptions();
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return $this->builder->getTypeOption($name, $default);
    }

    /**
     * @return Input
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * {@inheritdoc}
     */
    public function setInput(Input $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        if (is_callable($this->count)) {
            $this->count = call_user_func($this->count);
        }

        return (int) $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        if (is_callable($this->items)) {
            $this->items = call_user_func($this->items);
        }

        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return InputKeys
     */
    public function getInputKeys()
    {
        return $this->getOption('inputKeys');
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->getItems();

        reset($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        $this->getItems();

        return current($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        $this->getItems();

        return key($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        $this->getItems();

        next($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        $this->getItems();

        return key($this->items) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        $this->getItems();

        return count($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        $this->getItems();

        return array_key_exists($offset, $this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        $this->getItems();

        return $this->items[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->getItems();

        if (null === $offset) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        $this->getItems();

        unset($this->items[$offset]);
    }
}
