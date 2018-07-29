<?php

namespace Nadia\Bundle\PaginatorBundle\Pagination;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\InputInterface;
use Symfony\Component\Form\FormView;

/**
 * Class Pagination
 */
class Pagination
{
    private $builder;
    private $options;
    private $input;
    private $form;
    private $count;
    private $items;
    private $template;
    private $lastPage;

    public function __construct(PaginatorBuilder $builder, array $options, InputInterface $input, $form, $count, $items)
    {
        $this->builder = $builder;
        $this->options = $options;
        $this->input = $input;
        $this->form = $form;
        $this->count = $count;
        $this->items = $items;
        $this->template = $options['template'];


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
        return $this->form;
    }

    /**
     * @return FormView
     */
    public function getFilterForm()
    {
        return $this->form[$this->options['queryParams']->filter];
    }

    /**
     * @return FormView
     */
    public function getSearchForm()
    {
        return $this->form[$this->options['queryParams']->search];
    }

    /**
     * @return FormView
     */
    public function getSortForm()
    {
        return $this->form[$this->options['queryParams']->sort];
    }

    /**
     * @return FormView
     */
    public function getLimitForm()
    {
        return $this->form[$this->options['queryParams']->limit];
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
}
