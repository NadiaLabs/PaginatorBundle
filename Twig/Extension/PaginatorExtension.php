<?php

namespace Nadia\Bundle\PaginatorBundle\Twig\Extension;

use Nadia\Bundle\PaginatorBundle\Configuration\Sort;
use Nadia\Bundle\PaginatorBundle\Pagination\Pagination;
use Nadia\Bundle\PaginatorBundle\Twig\ContextProcessor;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginatorExtension extends AbstractExtension
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
            new TwigFunction('nadia_paginator_pages',      array($this, 'pages'),     $options),
            new TwigFunction('nadia_paginator_searches',   array($this, 'searches'),  $options),
            new TwigFunction('nadia_paginator_filters',    array($this, 'filters'),   $options),
            new TwigFunction('nadia_paginator_sorts',      array($this, 'sorts'),     $options),
            new TwigFunction('nadia_paginator_sort_link',  array($this, 'sortLink'),  $options),
            new TwigFunction('nadia_paginator_page_sizes', array($this, 'pageSizes'), $options),
        );
    }

    /**
     * Renders the pages template
     *
     * @param Environment $env
     * @param Pagination  $pagination
     * @param array       $options    Format: {
     *     @var int    $range            The range of pages, default is 8
     *     @var string $firstPageText    First page display text
     *     @var string $lastPageText     Last page display text
     *     @var string $previousPageText Previous page display text
     *     @var string $nextPageText     Next page display text
     * }
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function pages(Environment $env, Pagination $pagination, array $options = [])
    {
        $template = $pagination->getOption('pagesTemplate');
        $viewData = $this->processor->pages($pagination, $options);

        return $env->render($template, $viewData);
    }

    /**
     * Renders the searches template
     *
     * @param Environment $env
     * @param Pagination  $pagination A Pagination instance
     * @param array       $options    Format: {
     *     @var array $attr Attributes for each search's div container, ex: array('class' => 'foobar', ...)
     * }
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function searches(Environment $env, Pagination $pagination, array $options = [])
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
     * @param Environment $env
     * @param Pagination  $pagination A Pagination instance
     * @param array       $options    Format: {
     *     @var array $attr Attributes for each filter's div container, ex: array('class' => 'foobar', ...)
     * }
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function filters(Environment $env, Pagination $pagination, array $options = [])
    {
        if (!$pagination->getBuilder()->hasFilter()) {
            return '';
        }

        $template = $pagination->getOption('filtersTemplate');
        $viewData = $this->processor->filters($pagination, $options);

        return $env->render($template, $viewData);
    }

    /**
     * Renders the sort selection template
     *
     * @param Environment $env
     * @param Pagination  $pagination
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sorts(Environment $env, Pagination $pagination)
    {
        if (!$pagination->getBuilder()->hasSort()) {
            return '';
        }

        $template = $pagination->getOption('sortsTemplate');
        $viewData = $this->processor->sorts($pagination);

        return $env->render($template, $viewData);
    }

    /**
     * Renders the sort link template
     *
     * @param Environment $env        A Twig_Environment instance
     * @param Pagination  $pagination A Pagination instance
     * @param string      $title      Sort title
     * @param string      $key        Sort key
     * @param string      $direction  Sort default direction (SortInterface::ASC or SortInterface::DESC)
     * @param array       $options    Format: {
     *     @var array $attr Html tag attributes, ex: array('class' => 'foobar', 'alt' => 'balabalabala...', ...)
     * }
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sortLink(
        Environment $env,
        Pagination $pagination,
        $title,
        $key,
        $direction = Sort::ASC,
        array $options = array()
    ) {
        if (!$pagination->getBuilder()->hasSort()) {
            return $title;
        }

        $template = $pagination->getOption('sortLinkTemplate');
        $viewData = $this->processor->sortLink($pagination, $title, $key, $direction, $options);

        return $env->render($template, $viewData);
    }

    /**
     * Renders the page sizes template
     *
     * @param Environment $env
     * @param Pagination  $pagination
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function pageSizes(Environment $env, Pagination $pagination)
    {
        if (!$pagination->getBuilder()->hasPageSize()) {
            return '';
        }

        $template = $pagination->getOption('pageSizesTemplate');
        $viewData = $this->processor->pageSizes($pagination);

        return $env->render($template, $viewData);
    }
}
