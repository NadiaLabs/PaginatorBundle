<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * A collection of QueryProcessors
 *
 * Every QueryProcessor is a callable function, suggest to use `['MyClass', 'myCallbackMethod']` format.
 * QueryProcessors will handle query modification before execute a SQL query.
 */
class QueryProcessorCollection implements \ArrayAccess
{
    /**
     * @var callable[]
     */
    private $processors = [];

    /**
     * Get a query processor
     *
     * @param string $name Processor name, the same as processing target field name
     *
     * @return callable | null
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->processors[$name];
        }

        throw new \InvalidArgumentException('Query processor "'.$name.'" is not exists!');
    }

    /**
     * Add a query processor
     *
     * @param string   $name     Processor name, the same as processing target field name
     * @param callable $callback A Callback for processing a query
     *
     * @return $this
     */
    public function add($name, $callback)
    {
        $this->processors[$name] = $callback;

        return $this;
    }

    /**
     * Check a query processor is exists or not
     *
     * @param string $name Processor name, the same as processing target field name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->processors);
    }

    /**
     * @param string $name Processor name, the same as processing target field name
     *
     * @return $this
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->processors[$name]);
        }

        return $this;
    }

    /**
     * Get all query processors
     *
     * @return array
     */
    public function all()
    {
        return $this->processors;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->add($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
