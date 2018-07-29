<?php

namespace Nadia\Bundle\PaginatorBundle\Input;

/**
 * InputInterface
 */
interface InputInterface
{
    /**
     * @return array
     */
    public function getFilter();

    /**
     * @param array $filter
     *
     * @return $this
     */
    public function setFilter(array $filter);

    /**
     * @return array
     */
    public function getSearch();

    /**
     * @param array $search
     *
     * @return $this
     */
    public function setSearch(array $search);

    /**
     * @return string
     */
    public function getSort();

    /**
     * @param string $sort
     *
     * @return $this
     */
    public function setSort($sort);

    /**
     * @return int
     */
    public function getPage();

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage($page);

    /**
     * @return int
     */
    public function getLimit();

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit($limit);

    /**
     * @return int
     */
    public function getOffset();

    /**
     * @param int $page
     * @param int $limit
     */
    public function setOffset($page, $limit);
}
