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
    private $items = [];

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
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function setCount($count)
    {
        $this->count = (int) $count;
    }

    /**
     * @return array
     */
    public function getItems()
    {
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
        reset($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        next($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return key($this->items) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
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
        unset($this->items[$offset]);
    }
}
