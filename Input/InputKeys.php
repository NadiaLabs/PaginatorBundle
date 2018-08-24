<?php

namespace Nadia\Bundle\PaginatorBundle\Input;

/**
 * The definition of URL query parameter names
 */
class InputKeys
{
    /**
     * @var string
     */
    public $filter = '_f';
    /**
     * @var string
     */
    public $search = '_q';
    /**
     * @var string
     */
    public $sort = '_s';
    /**
     * @var string
     */
    public $page = '_p';
    /**
     * @var string
     */
    public $limit = '_l';
    /**
     * @var string
     */
    public $clear = '_c';
}
