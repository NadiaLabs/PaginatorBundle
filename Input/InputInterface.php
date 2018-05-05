<?php

namespace NadiaProject\Bundle\PaginatorBundle\Input;

/**
 * InputInterface
 */
interface InputInterface
{
    /**
     * @return array
     */
    public function getFilters();

    /**
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters);

    /**
     * @return string
     */
    public function getSearch();

    /**
     * @param string $keywords
     *
     * @return $this
     */
    public function setSearch($keywords);

    /**
     * @return array
     */
    public function getSorts();

    /**
     * @param array $sorts
     *
     * @return $this
     */
    public function setSorts(array $sorts);

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
    public function getPageSize();

    /**
     * @param int $pageSize
     *
     * @return $this
     */
    public function setPageSize($pageSize);
}
