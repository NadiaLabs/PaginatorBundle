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
     * @var array
     */
    private $search = [];
    /**
     * @var string
     */
    private $sort;
    /**
     * @var int
     */
    private $page;
    /**
     * @var int
     */
    private $limit;
    /**
     * @var int
     */
    private $offset;

    /**
     * Input constructor.
     *
     * @param array       $filter
     * @param array       $search
     * @param string|null $sort
     * @param int|null    $page
     * @param int|null    $limit
     */
    public function __construct(array $filter = [], array $search = [], $sort = null, $page = null, $limit = null)
    {
        $this->filter = $filter;
        $this->search = $search;
        $this->sort = $sort;
        $this->page = $page;
        $this->limit = $limit;

        $this->setOffset($page, $limit);
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
    public function setSort($sort)
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

        $this->setOffset($this->page, $this->limit);

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

        $this->setOffset($this->page, $this->limit);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function setOffset($page, $limit)
    {
        $this->offset = ($page - 1) * $limit;
    }
}
