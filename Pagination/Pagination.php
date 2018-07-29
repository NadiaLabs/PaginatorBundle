<?php

namespace Nadia\Bundle\PaginatorBundle\Pagination;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\InputInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class Pagination
 */
class Pagination
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
    private $count;
    /**
     * @var array
     */
    private $items;
    /**
     * @var int
     */
    private $lastPage;

    public function __construct(PaginatorBuilder $builder, array $options, InputInterface $input, FormInterface $form, $count, $items)
    {
        $this->builder = $builder;
        $this->options = $options;
        $this->input = $input;
        $this->form = $form;
        $this->count = $count;
        $this->items = $items;
        $this->lastPage = (int) ceil($count / $this->input->getLimit());
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
     * @return bool
     */
    public function hasFilterForm()
    {
        return $this->form->has($this->options['queryParams']->filter);
    }

    /**
     * @return FormView
     */
    public function getFilterForm()
    {
        return $this->form[$this->options['queryParams']->filter]->createView();
    }

    /**
     * @return bool
     */
    public function hasSearchForm()
    {
        return $this->form->has($this->options['queryParams']->search);
    }

    /**
     * @return FormView
     */
    public function getSearchForm()
    {
        return $this->form[$this->options['queryParams']->search]->createView();
    }

    /**
     * @return bool
     */
    public function hasSortForm()
    {
        return $this->form->has($this->options['queryParams']->sort);
    }

    /**
     * @return FormView
     */
    public function getSortForm()
    {
        return $this->form[$this->options['queryParams']->sort]->createView();
    }

    /**
     * @return bool
     */
    public function hasLimitForm()
    {
        return $this->form->has($this->options['queryParams']->limit);
    }

    /**
     * @return FormView
     */
    public function getLimitForm()
    {
        return $this->form[$this->options['queryParams']->limit]->createView();
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
        return $this->options['template'];
    }
}
