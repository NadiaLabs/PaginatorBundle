<?php

namespace NadiaProject\Bundle\PaginatorBundle\Builder;
use Doctrine\ORM\QueryBuilder;
use NadiaProject\Bundle\PaginatorBundle\Input\InputInterface;

/**
 * Class PaginatorBuilder
 */
class PaginatorBuilder
{
    private $filterFormParameters = [];
    private $searchFormParameters = [];
    private $searchColumns = [];
    private $sortColumns = [];
    private $filterProcessors = [];
    private $searchProcessor;
    private $sortProcessors = [];
    private $pageSizes = [5 => 5, 10 => 10, 20 => 20, 50 => 50, 100 => 100];

    /**
     * Setup a filter form
     *
     * @param string $name Filter name, the same as entity property name
     * @param string $formType
     * @param array $options
     *
     * @return $this
     */
    public function addFilterForm($name, $formType, array $options = [])
    {
        $parts = explode('.', $name);
        $count = count($parts);

        if (2 !== $count) {
            return $this;
        }

        $options = array_merge(['required' => false], $options);

        $this->filterFormParameters[$name] = [
            'alias' => $parts[0],
            'name' => $parts[1],
            'type' => $formType,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * Check a filter form is exists
     *
     * @param string $name Filter name, the same as entity property name
     *
     * @return bool
     */
    public function hasFilterForm($name)
    {
        return isset($this->filterFormParameters[$name]);
    }

    /**
     * Remove a filter form
     *
     * @param string $name Filter name, the same as entity property name
     *
     * @return $this
     */
    public function removeFilterForm($name)
    {
        if ($this->hasFilterForm($name)) {
            unset($this->filterFormParameters[$name]);
        }

        return $this;
    }

    /**
     * Setup search form
     *
     * @param array $names Search names, names are the same as entity property names
     * @param string $formType
     * @param array $options
     *
     * @return $this
     */
    public function setSearchForm(array $names, $formType, array $options = [])
    {
        $this->searchColumns = $names;
        $options = array_merge(['required' => false], $options);

        $this->searchFormParameters = [
            'type' => $formType,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * Remove search form
     *
     * @return $this
     */
    public function removeSearchForm()
    {
        $this->searchColumns = [];
        $this->searchFormParameters = [];

        return $this;
    }

    /**
     * Check a search form is exists
     *
     * @return bool
     */
    public function hasSearchForm()
    {
        return !empty($this->searchColumns);
    }

    /**
     * Add a valid sort column name, the same as entity property name
     *
     * @param string $name The sort column name, the same as entity property name
     *
     * @return $this
     */
    public function addSortColumn($name)
    {
        $this->sortColumns[$name] = $name;

        return $this;
    }

    /**
     * Check a valid sort column name is exists
     *
     * @param string $name The sort column name, the same as entity property name
     *
     * @return bool
     */
    public function hasSortColumn($name)
    {
        return isset($this->sortColumns[$name]);
    }

    /**
     * Remove a valid sort column name, the same as entity property name
     *
     * @param string $name The sort column name, the same as entity property name
     *
     * @return $this
     */
    public function removeSortColumn($name)
    {
        if ($this->hasSortColumn($name)) {
            unset($this->sortColumns[$name]);
        }

        return $this;
    }

    /**
     * Overwrite all valid sort column names, the same as entity property names
     *
     * @param string[] $names The sort column names, the same as entity property names
     *
     * @return $this
     */
    public function setSortColumns(array $names)
    {
        $this->sortColumns = [];

        foreach ($names as $name) {
            $this->sortColumns[$name] = $name;
        }

        return $this;
    }

    /**
     * Add a filter processor
     *
     * @param string $name The filter name
     * @param callable $callback A callable callback,
     *     function interface: function($qb, string $fieldName, mixed $value)
     *
     * @return $this
     */
    public function addFilterProcessor($name, $callback)
    {
        if (is_callable($callback)) {
            $this->filterProcessors[$name] = $callback;
        }

        return $this;
    }

    /**
     * Check a filter processor is exists
     *
     * @param string $name The filter name
     *
     * @return bool
     */
    public function hasFilterProcessor($name)
    {
        return isset($this->filterProcessors[$name]);
    }

    /**
     * Remove a filter processor
     *
     * @param string $name The filter name
     *
     * @return $this
     */
    public function removeFilterProcessor($name)
    {
        if ($this->hasFilterProcessor($name)) {
            unset($this->filterProcessors[$name]);
        }

        return $this;
    }

    /**
     * Setup default search processor
     *
     * @param callable $callback A callable callback,
     *     function interface: function($qb, array $fieldNames, mixed $value)
     *
     * @return $this
     */
    public function setSearchProcessor($callback)
    {
        if (is_callable($callback)) {
            $this->searchProcessor = $callback;
        }

        return $this;
    }

    /**
     * Remove default search processor
     *
     * @return $this
     */
    public function removeSearchProcessor()
    {
        $this->searchProcessor = null;

        return $this;
    }

    /**
     * Add a sort processor
     *
     * @param string $name The sort processor name
     * @param callable $callback A callable callback,
     *     function interface: function($qb, string $fieldName, string $direction)
     *
     * @return $this
     */
    public function addSortProcessor($name, $callback)
    {
        if (is_callable($callback)) {
            $this->sortProcessors[$name] = $callback;
        }

        return $this;
    }

    /**
     * Check a sort processor is exists
     *
     * @param string $name The sort processor name
     *
     * @return bool
     */
    public function hasSortProcessor($name)
    {
        return isset($this->sortProcessors[$name]);
    }

    /**
     * Remove a sort processor
     *
     * @param string $name The sort processor name
     *
     * @return $this
     */
    public function removeSortProcessor($name)
    {
        if ($this->hasSortProcessor($name)) {
            unset($this->sortProcessors[$name]);
        }

        return $this;
    }

    /**
     * Set a valid page size list
     *
     * @param int[] $pageSizes
     *
     * @return $this
     */
    public function setPageSizes(array $pageSizes)
    {
        $this->pageSizes = $pageSizes;

        return $this;
    }

    /**
     * Check a page size is exists
     *
     * @param int $pageSize
     *
     * @return bool
     */
    public function hasPageSize($pageSize)
    {
        return isset($this->pageSizes[$pageSize]);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function getFilterFormParameters($name)
    {
        return $this->filterFormParameters[$name];
    }

    /**
     * @return array
     */
    public function getAllFilterFormParameters()
    {
        return $this->filterFormParameters;
    }

    /**
     * @return array
     */
    public function getSearchFormParameters()
    {
        return $this->searchFormParameters;
    }

    /**
     * @return array
     */
    public function getSearchColumns()
    {
        return $this->searchColumns;
    }

    /**
     * @return array
     */
    public function getSortColumns()
    {
        return $this->sortColumns;
    }

    /**
     * @return array
     */
    public function getFilterProcessors()
    {
        return $this->filterProcessors;
    }

    /**
     * @return mixed
     */
    public function getSearchProcessor()
    {
        if (is_callable($this->searchProcessor)) {
            return $this->searchProcessor;
        } else {
            $this->searchProcessor = [$this, 'runDefaultSearchProcess'];
        }

        return $this->searchProcessor;
    }

    /**
     * @param QueryBuilder $qb
     * @param array $fieldNames
     * @param $value
     */
    public function runDefaultSearchProcess(QueryBuilder $qb, array $fieldNames, $value)
    {
        $where = [];

        foreach ($fieldNames as $fieldName) {
            $where[] = $qb->expr()->like($fieldName, $qb->expr()->literal('%' . $value . '%'));
        }

        $qb->andWhere(call_user_func_array([$qb->expr(), 'orX'], $where));
    }

    /**
     * @return array
     */
    public function getSortProcessors()
    {
        return $this->sortProcessors;
    }

    /**
     * @return array
     */
    public function getPageSizes()
    {
        return $this->pageSizes;
    }

    /**
     * @param InputInterface $input
     */
    public function validateInput(InputInterface $input)
    {
        $input->setFilters($this->getValidFilters($input->getFilters()));
        $input->setSorts($this->getValidSorts($input->getSorts()));
        $input->setSearch($this->getValidSearch($input->getSearch()));
        $input->setPageSize($this->getValidPageSize($input->getPageSize()));
    }

    /**
     * @param array $filters
     * @param array $default
     *
     * @return array
     */
    public function getValidFilters(array $filters, array $default = [])
    {
        $validFilters = [];

        foreach ($filters as $alias => $filter) {
            if (!is_array($filter)) {
                continue;
            }

            foreach ($filter as $key => $value) {
                if ($this->hasFilterForm($alias . '.' . $key)) {
                    $validFilters[$alias][$key] = $value;
                }
            }
        }

        return $validFilters;
    }

    /**
     * @param array $sorts
     *
     * @return array
     */
    public function getValidSorts(array $sorts)
    {
        $validSorts = [];

        foreach ($sorts as $sortBy => $sortDirection) {
            if (!$this->hasSortColumn($sortBy)) {
                continue;
            }

            if (!in_array($sortDirection, ['ASC', 'DESC'])) {
                $sortDirection = 'ASC';
            }

            $validSorts[$sortBy] = $sortDirection;
        }

        return $validSorts;
    }

    /**
     * @param string $search
     *
     * @return string
     */
    public function getValidSearch($search)
    {
        return $this->hasSearchForm() ? $search : '';
    }

    /**
     * @param int $pageSize
     * @param int $default
     *
     * @return int
     */
    public function getValidPageSize($pageSize, $default = 10)
    {
        if ($this->hasPageSize($pageSize)) {
            return (int) $pageSize;
        }

        if ($this->hasPageSize($default)) {
            return (int) $default;
        }

        if (!empty($this->pageSizes)) {
            return (int) current($this->pageSizes);
        }

        return 0;
    }
}
