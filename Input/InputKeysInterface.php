<?php

namespace Nadia\Bundle\PaginatorBundle\Input;

/**
 * The Interface for the definition of URL query parameter names
 */
interface InputKeysInterface
{
    /**
     * @return string
     */
    public function getFilter();

    /**
     * @return string
     */
    public function getSearch();

    /**
     * @return string
     */
    public function getSort();

    /**
     * @return string
     */
    public function getPage();

    /**
     * @return string
     */
    public function getPageSize();

    /**
     * @return string
     */
    public function getReset();
}
