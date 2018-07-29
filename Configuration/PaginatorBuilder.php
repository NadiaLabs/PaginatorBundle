<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaginatorBuilder
 */
class PaginatorBuilder
{
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var QueryProcessorCollection
     */
    private $filterQueryProcessors;
    /**
     * @var SearchBuilder
     */
    private $searchBuilder;
    /**
     * @var QueryProcessorCollection
     */
    private $searchQueryProcessors;
    /**
     * @var SortBuilder
     */
    private $sortBuilder;
    /**
     * @var LimitBuilder
     */
    private $limitBuilder;

    /**
     * @param string $className Filter class name
     * @param array  $options   Filter options
     */
    public function setFilter($className, array $options = [])
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Could not load type "'.$className.'": class does not exist.');
        }
        if (!is_subclass_of($className, 'Nadia\Bundle\PaginatorBundle\Configuration\FilterInterface')) {
            throw new \InvalidArgumentException('Could not load type "'.$className.'": class does not implement "Nadia\Bundle\PaginatorBundle\Configuration\FilterInterface".');
        }

        /** @var FilterInterface $filter */
        $filter = new $className();
        $this->filterBuilder = new FilterBuilder();
        $this->filterQueryProcessors = new QueryProcessorCollection();
        $optionResolver = new OptionsResolver();

        $filter->configureOptions($optionResolver);

        $options = $optionResolver->resolve($options);

        $filter->build($this->filterBuilder, $this->filterQueryProcessors, $options);
    }

    /**
     * @param string $className Search class name
     * @param array  $options   Search options
     */
    public function setSearch($className, array $options = [])
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Could not load class "'.$className.'": class does not exist.');
        }
        if (!is_subclass_of($className, 'Nadia\Bundle\PaginatorBundle\Configuration\SearchInterface')) {
            throw new \InvalidArgumentException('Could not load class "'.$className.'": class does not implement "Nadia\Bundle\PaginatorBundle\Configuration\SearchInterface".');
        }

        /** @var SearchInterface $search */
        $search = new $className();
        $this->searchBuilder = new SearchBuilder();
        $this->searchQueryProcessors = new QueryProcessorCollection();
        $optionResolver = new OptionsResolver();

        $search->configureOptions($optionResolver);

        $options = $optionResolver->resolve($options);

        $search->build($this->searchBuilder, $this->searchQueryProcessors, $options);
    }

    /**
     * @param string $className Sort class name
     */
    public function setSort($className)
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Could not load class "'.$className.'": class does not exist.');
        }
        if (!is_subclass_of($className, 'Nadia\Bundle\PaginatorBundle\Configuration\SortInterface')) {
            throw new \InvalidArgumentException('Could not load class "'.$className.'": class does not implement "Nadia\Bundle\PaginatorBundle\Configuration\SortInterface".');
        }

        /** @var SortInterface $sort */
        $sort = new $className();
        $this->sortBuilder = new SortBuilder();

        $sort->build($this->sortBuilder);
    }

    /**
     * @param string $className Limit class name
     */
    public function setLimit($className)
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Could not load class "'.$className.'": class does not exist.');
        }
        if (!is_subclass_of($className, 'Nadia\Bundle\PaginatorBundle\Configuration\LimitInterface')) {
            throw new \InvalidArgumentException('Could not load class "'.$className.'": class does not implement "Nadia\Bundle\PaginatorBundle\Configuration\LimitInterface".');
        }

        /** @var LimitInterface $limit */
        $limit = new $className();
        $this->limitBuilder = new LimitBuilder();

        $limit->build($this->limitBuilder);
    }

    /**
     * @return FilterBuilder
     */
    public function getFilterBuilder()
    {
        return $this->filterBuilder;
    }

    /**
     * @return SearchBuilder
     */
    public function getSearchBuilder()
    {
        return $this->searchBuilder;
    }

    /**
     * @return SortBuilder
     */
    public function getSortBuilder()
    {
        return $this->sortBuilder;
    }

    /**
     * @return LimitBuilder
     */
    public function getLimitBuilder()
    {
        return $this->limitBuilder;
    }

    /**
     * @return QueryProcessorCollection
     */
    public function getFilterQueryProcessors()
    {
        return $this->filterQueryProcessors;
    }

    /**
     * @return QueryProcessorCollection
     */
    public function getSearchQueryProcessors()
    {
        return $this->searchQueryProcessors;
    }

    /**
     * @return bool
     */
    public function hasFilter()
    {
        return $this->filterBuilder instanceof FilterBuilder && $this->filterBuilder->count();
    }

    /**
     * @return bool
     */
    public function hasSearch()
    {
        return $this->searchBuilder instanceof SearchBuilder && $this->searchBuilder->count();
    }

    /**
     * @return bool
     */
    public function hasSort()
    {
        return $this->sortBuilder instanceof SortBuilder && $this->sortBuilder->count();
    }

    /**
     * @return bool
     */
    public function hasLimit()
    {
        return $this->limitBuilder instanceof LimitBuilder;
    }
}
