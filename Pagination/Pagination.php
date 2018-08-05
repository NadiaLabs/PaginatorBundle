<?php

namespace Nadia\Bundle\PaginatorBundle\Pagination;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\InputInterface;
use Nadia\Bundle\PaginatorBundle\Input\QueryParameterDefinition;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class Pagination
 */
class Pagination implements \Countable, \Iterator, \ArrayAccess
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
     * @var InputInterface
     */
    private $input;
    /**
     * @var FormInterface
     */
    private $form;
    /**
     * @var int
     */
    private $total;
    /**
     * @var array
     */
    private $items;
    /**
     * @var int
     */
    private $lastPage;

    /**
     * Pagination constructor.
     *
     * @param PaginatorBuilder $builder
     * @param array            $options
     * @param InputInterface   $input
     * @param FormInterface    $form
     * @param int              $total
     * @param mixed            $items
     */
    public function __construct(PaginatorBuilder $builder, array $options, InputInterface $input, FormInterface $form, $total, $items)
    {
        $this->builder = $builder;
        $this->options = $options;
        $this->input = $input;
        $this->form = $form;
        $this->total = $total;
        $this->items = $items;
        $this->lastPage = (int) ceil($total / $this->input->getLimit());
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
        return $this->options;
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
     * @return InputInterface
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return FormView
     */
    public function getForm()
    {
        return $this->form->createView();
    }

    /**
     * @param FormView $form
     *
     * @return bool
     */
    public function hasFilterForm(FormView $form)
    {
        return isset($form[$this->getQueryParamDef()->filter]);
    }

    /**
     * @param FormView $form
     *
     * @return FormView
     */
    public function getFilterForm(FormView $form)
    {
        return $form[$this->getQueryParamDef()->filter];
    }

    /**
     * @param FormView $form
     *
     * @return bool
     */
    public function hasSearchForm(FormView $form)
    {
        return isset($form[$this->getQueryParamDef()->search]);
    }

    /**
     * @param FormView $form
     *
     * @return FormView
     */
    public function getSearchForm(FormView $form)
    {
        return $form[$this->getQueryParamDef()->search];
    }

    /**
     * @param FormView $form
     *
     * @return bool
     */
    public function hasSortForm(FormView $form)
    {
        return isset($form[$this->getQueryParamDef()->sort]);
    }

    /**
     * @param FormView $form
     *
     * @return FormView
     */
    public function getSortForm(FormView $form)
    {
        return $form[$this->getQueryParamDef()->sort];
    }

    /**
     * @param FormView $form
     *
     * @return bool
     */
    public function hasLimitForm(FormView $form)
    {
        return isset($form[$this->getQueryParamDef()->limit]);
    }

    /**
     * @param FormView $form
     *
     * @return FormView
     */
    public function getLimitForm(FormView $form)
    {
        return $form[$this->getQueryParamDef()->limit];
    }

    /**
     * @return QueryParameterDefinition
     */
    public function getQueryParamDef()
    {
        return $this->options['queryParams'];
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->options['template'];
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
