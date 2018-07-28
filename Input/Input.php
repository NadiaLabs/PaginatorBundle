<?php

namespace Nadia\Bundle\PaginatorBundle\Input;

/**
 * class Input
 */
class Input implements InputInterface
{
    /**
     * @var array
     */
    private $filter = [];
    /**
     * @var string
     */
    private $search;
    /**
     * @var array
     */
    private $sort = [];
    /**
     * @var int
     */
    private $page;
    /**
     * @var int
     */
    private $limit;

    /**
     * Input constructor.
     *
     * @param array    $filter
     * @param array    $search
     * @param array    $sort
     * @param int|null $page
     * @param int|null $limit
     */
    public function __construct(array $filter = [], array $search = [], array $sort = [], $page = null, $limit = null)
    {
        $this->filter = $filter;
        $this->search = $search;
        $this->sort = $sort;
        $this->page = $page;
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilter(array $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * {@inheritdoc}
     */
    public function setSearch(array $search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * {@inheritdoc}
     */
    public function setSort(array $sort)
    {
        $this->sort = $sort;
    }

    /**
     * {@inheritdoc}
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * {@inheritdoc}
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * {@inheritdoc}
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
}
