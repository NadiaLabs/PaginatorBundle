<?php

namespace Nadia\Bundle\PaginatorBundle\Pagination;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class Pagination
 */
class Pagination extends AbstractPagination
{
    /**
     * @var string
     */
    private $route;
    /**
     * @var array
     */
    private $routeParams = array();

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var FormView
     */
    private $formView;

    /**
     * Get current route name
     *
     * @return string
     */
    public function getCurrentRoute()
    {
        return $this->route;
    }

    /**
     * Set current route name
     *
     * @param string $route
     *
     * @return $this
     */
    public function setCurrentRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get current route parameters
     *
     * @return array
     */
    public function getCurrentRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * Set current route parameters
     *
     * @param array $params
     *
     * @return $this
     */
    public function setCurrentRouteParams(array $params)
    {
        $this->routeParams = $params;

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
     * @return bool
     */
    public function hasFilterForm()
    {
        $form = $this->getFormView();

        return isset($form[$this->getInputKeys()->getFilter()]);
    }

    /**
     * @return FormView
     */
    public function getFilterForm()
    {
        $form = $this->getFormView();

        return $form[$this->getInputKeys()->getFilter()];
    }

    /**
     * @return bool
     */
    public function hasSearchForm()
    {
        $form = $this->getFormView();

        return isset($form[$this->getInputKeys()->getSearch()]);
    }

    /**
     * @return FormView
     */
    public function getSearchForm()
    {
        $form = $this->getFormView();

        return $form[$this->getInputKeys()->getSearch()];
    }

    /**
     * @return bool
     */
    public function hasSortForm()
    {
        $form = $this->getFormView();

        return isset($form[$this->getInputKeys()->getSort()]);
    }

    /**
     * @return FormView
     */
    public function getSortForm()
    {
        $form = $this->getFormView();

        return $form[$this->getInputKeys()->getSort()];
    }

    /**
     * @return bool
     */
    public function hasPageSizeForm()
    {
        $form = $this->getFormView();

        return isset($form[$this->getInputKeys()->getPageSize()]);
    }

    /**
     * @return FormView
     */
    public function getPageSizeForm()
    {
        $form = $this->getFormView();

        return $form[$this->getInputKeys()->getPageSize()];
    }
}
