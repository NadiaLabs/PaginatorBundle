<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * Class PaginatorBuilder
 */
class PaginatorBuilder
{
    /**
     * @var string Filter class name
     */
    private $filter;
    /**
     * @var string Search class name
     */
    private $search;
    /**
     * @var string Sort class name
     */
    private $sort;
    /**
     * @var string Limit class name
     */
    private $limit;
    /**
     * @var array Misc options
     */
    private $options = [
        'filter' => [],
        'search' => [],
        'sort' => [],
    ];

    /**
     * @param string $filter  Filter class name
     * @param array  $options Filter options
     */
    public function setFilter($filter, array $options = [])
    {
        $this->filter = $filter;
        $this->options['filter'] = $options;
    }

    /**
     * @param string $search  Search class name
     * @param array  $options Search options
     */
    public function setSearch($search, array $options = [])
    {
        $this->search = $search;
        $this->options['search'] = $options;
    }

    /**
     * @param string $sort    Sort class name
     * @param array  $options Sort options
     */
    public function setSort($sort, array $options = [])
    {
        $this->sort = $sort;
        $this->options['sort'] = $options;
    }

    /**
     * @param string $limit Limit class name
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function getSort()
    {
        return $this->sort;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getFilterOptions()
    {
        return $this->options['filter'];
    }

    public function getSearchOptions()
    {
        return $this->options['search'];
    }

    public function getSortOptions()
    {
        return $this->options['sort'];
    }
}
