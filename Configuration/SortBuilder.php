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
     * Format: ['foo title' => 'foo', 'foo ASC title' => 'foo ASC', 'foo bar sort title' => 'foo ASC, bar DESC', ...]
     *
     * @var array
     */
    private $sorts = [];

    /**
     * Add a sort statement
     *
     * @param string $statement The sort statement, ex: 'foo', 'foo ASC', 'foo ASC, bar DESC'
     * @param string $title     The sort title
     *
     * @return $this
     */
    public function add($statement, $title)
    {
        if (!empty($statement)) {
            $this->sorts[$title] = $statement;
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
