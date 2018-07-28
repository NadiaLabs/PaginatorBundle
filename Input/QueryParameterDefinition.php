<?php

namespace Nadia\Bundle\PaginatorBundle\Input;

/**
 * Define query parameter field names
 */
class QueryParameterDefinition
{
    /**
     * @var string
     */
    public $filter = 'filter';
    /**
     * @var string
     */
    public $search = 'search';
    /**
     * @var string
     */
    public $sort = 'sort';
    /**
     * @var string
     */
    public $page = 'page';
    /**
     * @var string
     */
    public $limit = 'limit';
    /**
     * @var string
     */
    public $clear = '__clear_all_parameters';
}
