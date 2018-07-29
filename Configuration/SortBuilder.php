<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * Class SortBuilder
 */
class SortBuilder
{
    /**
     * Sort statements
     *
     * Format: ['foo', 'foo ASC', 'foo ASC, bar DESC', ...]
     *
     * @var array
     */
    private $sorts = [];

    /**
     * Add a sort statement
     *
     * @param string $statement The sort statement, ex: 'foo', 'foo ASC', 'foo ASC, bar DESC'
     *
     * @return $this
     */
    public function add($statement)
    {
        if (!empty($statement)) {
            $this->sorts[] = $statement;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->sorts;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->sorts);
    }
}
