<?php

namespace Nadia\Bundle\PaginatorBundle\Input;

use Nadia\Bundle\PaginatorBundle\Configuration\SortInterface;

/**
 * class Input
 */
class Input
{
    /**
     * @var array
     */
    private $filter = array();
    /**
     * @var array
     */
    private $search = array();
    /**
     * @var array
     */
    private $sort = array();
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
    public function __construct(array $filter = array(), array $search = array(), $sort = null, $page = null, $limit = null)
    {
        $this->filter = $filter;
        $this->search = $search;
        $this->page = $page;
        $this->limit = $limit;
        $this->offset = $this->calculateOffset($this->page, $this->limit);

        $this->setSort($sort);
    }

    /**
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param array $filter
     *
     * @return $this
     */
    public function setFilter(array $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return array
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param array $search
     *
     * @return $this
     */
    public function setSearch(array $search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * @return array Format: array('key' => 'sort key', 'direction' => 'sort direction')
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     *
     * @return $this
     */
    public function setSort($sort)
    {
        $sort = trim($sort);

        if (empty($sort)) {
            return $this;
        }

        $parts = explode(' ', $sort, 2);

        if (2 === count($parts)) {
            $direction = $parts[1] === SortInterface::DESC ? SortInterface::DESC : SortInterface::ASC;

            $this->sort = array('key' => $parts[0], 'direction' => $direction);
        } else {
            $this->sort = array('key' => $parts[0], 'direction' => SortInterface::ASC);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSort()
    {
        return !empty($this->sort['key']) && !empty($this->sort['direction']);
    }

    /**
     * @return int|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = $page;

        $this->setOffset($this->calculateOffset($this->page, $this->limit));

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        $this->setOffset($this->calculateOffset($this->page, $this->limit));

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return int
     */
    private function calculateOffset($page, $limit)
    {
        return ($page - 1) * $limit;
    }
}
