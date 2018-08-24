<?php

namespace Nadia\Bundle\PaginatorBundle\Pagination;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\Input;
use Nadia\Bundle\PaginatorBundle\Input\InputKeys;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

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
     * @var array
     */
    private $options = [];

    /**
     * @var Input
     */
    private $input;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var FormView
     */
    private $formView;

    /**
     * @var int
     */
    private $count;

    /**
     * @var array
     */
    private $items;

    /**
     * @return PaginatorBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * {@inheritdoc}
     */
    public function setBuilder(PaginatorBuilder $builder)
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : $default;
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
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return FormView
     */
    public function getFormView()
    {
        if (!$this->formView instanceof FormView) {
            $this->formView = $this->form->createView();
        }

        return $this->formView;
    }

    /**
     * @param FormView $form
     *
     * @return bool
     */
    public function hasFilterForm(FormView $form)
    {
        return isset($form[$this->getInputKeys()->filter]);
    }

    /**
     * @param FormView $form
     *
     * @return FormView
     */
    public function getFilterForm(FormView $form)
    {
        return $form[$this->getInputKeys()->filter];
    }

    /**
     * @param FormView $form
     *
     * @return bool
     */
    public function hasSearchForm(FormView $form)
    {
        return isset($form[$this->getInputKeys()->search]);
    }

    /**
     * @param FormView $form
     *
     * @return FormView
     */
    public function getSearchForm(FormView $form)
    {
        return $form[$this->getInputKeys()->search];
    }

    /**
     * @param FormView $form
     *
     * @return bool
     */
    public function hasSortForm(FormView $form)
    {
        return isset($form[$this->getInputKeys()->sort]);
    }

    /**
     * @param FormView $form
     *
     * @return FormView
     */
    public function getSortForm(FormView $form)
    {
        return $form[$this->getInputKeys()->sort];
    }

    /**
     * @param FormView $form
     *
     * @return bool
     */
    public function hasLimitForm(FormView $form)
    {
        return isset($form[$this->getInputKeys()->limit]);
    }

    /**
     * @param FormView $form
     *
     * @return FormView
     */
    public function getLimitForm(FormView $form)
    {
        return $form[$this->getInputKeys()->limit];
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
        return $this->options['inputKeys'];
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
