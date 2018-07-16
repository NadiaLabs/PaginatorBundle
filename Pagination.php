<?php

namespace Nadia\Bundle\PaginatorBundle;

use ArrayAccess;
use Countable;
use Iterator;
use Nadia\Bundle\PaginatorBundle\Builder\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\Input;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Pagination
 */
class Pagination implements Countable, Iterator, ArrayAccess
{
    private $paginatorBuilder;
    private $paginatorOptions;
    private $input;
    private $request;
    private $currentPage;
    private $pageSize;
    private $maxPageSize;
    private $count;
    private $items;
    private $template;
    private $filtersTemplate;
    private $sortTemplate;
    private $form;

    public function __construct(PaginatorBuilder $paginatorBuilder, array $paginatorOptions, Input $input, Request $request, $count, $items, $form)
    {
        $this->paginatorBuilder = $paginatorBuilder;
        $this->paginatorOptions = $paginatorOptions;
        $this->input = $input;
        $this->request = $request;
        $this->currentPage = $input->getPage();
        $this->pageSize = $input->getPageSize();
        $this->count = $count;
        $this->items = $items;
        $this->template = $paginatorOptions['templatePagination'];
        $this->filtersTemplate = $paginatorOptions['templateFilters'];
        $this->sortTemplate = $paginatorOptions['templateSort'];
        $this->form = $form;

        $this->maxPageSize = (int) ceil($count / $this->pageSize);
    }

    /**
     * @return PaginatorBuilder
     */
    public function getPaginatorBuilder()
    {
        return $this->paginatorBuilder;
    }

    /**
     * @return array
     */
    public function getPaginatorOptions()
    {
        return $this->paginatorOptions;
    }

    /**
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function getPaginatorOption($name, $default = null)
    {
        return array_key_exists($name, $this->paginatorOptions) ? $this->paginatorOptions[$name] : $default;
    }

    /**
     * @return Input
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @return int
     */
    public function getMaxPageSize()
    {
        return $this->maxPageSize;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
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
        return $this->template;
    }

    /**
     * @return string
     */
    public function getFiltersTemplate()
    {
        return $this->filtersTemplate;
    }

    /**
     * @return string
     */
    public function getSortTemplate()
    {
        return $this->sortTemplate;
    }

    /**
     * @return FormView
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return FormView
     */
    public function getFilterForm()
    {
        return $this->form[$this->paginatorOptions['paramNameFilter']];
    }

    /**
     * @return FormView
     */
    public function getSearchForm()
    {
        return $this->form[$this->paginatorOptions['paramNameSearch']];
    }

    /**
     * @return FormView
     */
    public function getPageSizeForm()
    {
        return $this->form[$this->paginatorOptions['paramNamePageSize']];
    }

    /**
     * {@inheritDoc}
     */
    public function rewind() {
        reset($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function current() {
        return current($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function key() {
        return key($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function next() {
        next($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function valid() {
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
