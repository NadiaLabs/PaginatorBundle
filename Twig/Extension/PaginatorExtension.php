<?php

namespace Nadia\Bundle\PaginatorBundle\Twig\Extension;

use Nadia\Bundle\PaginatorBundle\Configuration\SortInterface;
use Nadia\Bundle\PaginatorBundle\Pagination\Pagination;
use Nadia\Bundle\PaginatorBundle\Twig\ContextProcessor;

class PaginatorExtension extends \Twig_Extension
{
    /**
     * @var ContextProcessor
     */
    protected $processor;

    /**
     * PaginatorExtension constructor.
     *
     * @param ContextProcessor $processor
     */
    public function __construct(ContextProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        $options = array('is_safe' => array('html'), 'needs_environment' => true);

        return array(
            new \Twig_SimpleFunction('nadia_paginator_pages',      array($this, 'pages'),     $options),
            new \Twig_SimpleFunction('nadia_paginator_searches',   array($this, 'searches'),  $options),
            new \Twig_SimpleFunction('nadia_paginator_filters',    array($this, 'filters'),   $options),
            new \Twig_SimpleFunction('nadia_paginator_sort_form',  array($this, 'sortForm'),  $options),
            new \Twig_SimpleFunction('nadia_paginator_sort_link',  array($this, 'sortLink'),  $options),
            new \Twig_SimpleFunction('nadia_paginator_limit_form', array($this, 'limitForm'), $options),
        );
    }

    /**
     * Renders the pages template
     *
     * @param \Twig_Environment $env
     * @param Pagination        $pagination
     * @param array             $options    Format: {
     *     @var int    $range            The range of pages, default is 8
     *     @var string $firstPageText    First page display text
     *     @var string $lastPageText     Last page display text
     *     @var string $previousPageText Previous page display text
     *     @var string $nextPageText     Next page display text
     * }
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function pages(\Twig_Environment $env, Pagination $pagination, array $options = [])
    {
        $template = $pagination->getOption('pagesTemplate');
        $viewData = $this->processor->pages($pagination, $options);

        return $env->render($template, $viewData);
    }

    /**
     * Renders the searches template
     *
     * @param \Twig_Environment $env
     * @param Pagination        $pagination A Pagination instance
     * @param array             $options    Format: {
     *     @var array $attr Attributes for each search's div container, ex: array('class' => 'foobar', ...)
     * }
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function searches(\Twig_Environment $env, Pagination $pagination, array $options = [])
    {
        if (!$pagination->getBuilder()->hasSearch()) {
            return '';
        }

        $template = $pagination->getOption('searchesTemplate');
        $viewData = $this->processor->searches($pagination, $options);

        return $env->render($template, $viewData);
    }

    /**
     * Renders the filters template
     *
     * @param \Twig_Environment $env
     * @param Pagination        $pagination A Pagination instance
     * @param array             $options    Format: {
     *     @var array $attr Attributes for each filter's div container, ex: array('class' => 'foobar', ...)
     * }
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function filters(\Twig_Environment $env, Pagination $pagination, array $options = [])
    {
        if (!$pagination->getBuilder()->hasFilter()) {
            return '';
        }

        $template = $pagination->getOption('filtersTemplate');
        $viewData = $this->processor->filters($pagination, $options);

        return $env->render($template, $viewData);
    }

    /**
     * Renders the sort form template
     *
     * @param \Twig_Environment $env
     * @param Pagination $pagination
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sortForm(\Twig_Environment $env, Pagination $pagination)
    {
        if (!$pagination->getBuilder()->hasSort()) {
            return '';
        }

        $template = $pagination->getOption('sortFormTemplate');
        $viewData = $this->processor->sortForm($pagination);

        return $env->render($template, $viewData);
    }

    /**
     * Renders the sort link template
     *
     * @param \Twig_Environment $env        A Twig_Environment instance
     * @param Pagination        $pagination A Pagination instance
     * @param string            $title      Sort title
     * @param string            $key        Sort key
     * @param string            $direction  Sort default direction (SortInterface::ASC or SortInterface::DESC)
     * @param array             $options    Format: {
     *     @var array $attr Html tag attributes, ex: array('class' => 'foobar', 'alt' => 'balabalabala...', ...)
     * }
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sortLink(\Twig_Environment $env, Pagination $pagination, $title, $key, $direction = SortInterface::ASC, array $options = array())
    {
        if (!$pagination->getBuilder()->hasSort()) {
            return $title;
        }

        $template = $pagination->getOption('sortLinkTemplate');
        $viewData = $this->processor->sortLink($pagination, $title, $key, $direction, $options);

        return $env->render($template, $viewData);
    }

    /**
     * Renders the limit form template
     *
     * @param \Twig_Environment $env
     * @param Pagination $pagination
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function limitForm(\Twig_Environment $env, Pagination $pagination)
    {
        if (!$pagination->getBuilder()->hasLimit()) {
            return '';
        }

        $template = $pagination->getOption('limitFormTemplate');
        $viewData = $this->processor->limitForm($pagination);

        return $env->render($template, $viewData);
    }
}
