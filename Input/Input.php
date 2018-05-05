<?php

namespace NadiaProject\Bundle\PaginatorBundle\Input;

/**
 * class Input
 */
class Input implements InputInterface
{
    /**
     * @var array
     */
    private $filters = [];
    /**
     * @var string
     */
    private $search;
    /**
     * @var array
     */
    private $sorts = [];
    /**
     * @var int
     */
    private $page;
    /**
     * @var int
     */
    private $pageSize;

    /**
     * Input constructor.
     *
     * @param array $filters
     * @param string|null $search
     * @param array $sorts
     * @param int|null $page
     * @param int|null $pageSize
     */
    public function __construct(array $filters = [], $search = null, array $sorts = [], $page = null, $pageSize = null)
    {
        $this->filters = $filters;
        $this->search = $search;
        $this->sorts = $sorts;
        $this->page = $page;
        $this->pageSize = $pageSize;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;
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
    public function setSearch($search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSorts()
    {
        return $this->sorts;
    }

    /**
     * {@inheritdoc}
     */
    public function setSorts(array $sorts)
    {
        $this->sorts = $sorts;
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
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }
}
