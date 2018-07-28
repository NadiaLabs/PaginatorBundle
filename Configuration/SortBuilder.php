<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * Class SortBuilder
 */
class SortBuilder
{
    /**
     * Sort fields
     *
     * Format: ['columnName1' => 'defaultDirection1', 'columnName2' => 'defaultDirection2', ...]
     *
     * @var array
     */
    private $fields = [];

    /**
     * Add a sort field name and its default direction
     *
     * @param string $name             The sort field name
     * @param string $defaultDirection The default sort direction
     *
     * @return $this
     */
    public function add($name, $defaultDirection = 'ASC')
    {
        $this->fields[$name] = $defaultDirection;

        return $this;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->fields;
    }
}
