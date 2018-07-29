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
    public $filter = '__filter';
    /**
     * @var string
     */
    public $search = '__search';
    /**
     * @var string
     */
    public $sort = '__sort';
    /**
     * @var string
     */
    public $page = '__page';
    /**
     * @var string
     */
    public $limit = '__limit';
    /**
     * @var string
     */
    public $clear = '__clear_all_parameters';
}
