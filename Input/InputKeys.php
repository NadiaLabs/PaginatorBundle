<?php

namespace Nadia\Bundle\PaginatorBundle\Input;

/**
 * The definition of URL query parameter names
 */
class InputKeys implements InputKeysInterface
{
    const FILTER = '_f';
    const SEARCH = '_q';
    const SORT = '_s';
    const PAGE = '_p';
    const PAGE_SIZE = '_l';
    const RESET = '_r';

    /**
     * @return string
     */
    public function getFilter()
    {
        return self::FILTER;
    }

    /**
     * @return string
     */
    public function getSearch()
    {
        return self::SEARCH;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return self::SORT;
    }

    /**
     * @return string
     */
    public function getPage()
    {
        return self::PAGE;
    }

    /**
     * @return string
     */
    public function getPageSize()
    {
        return self::PAGE_SIZE;
    }

    /**
     * @return string
     */
    public function getReset()
    {
        return self::RESET;
    }
}
