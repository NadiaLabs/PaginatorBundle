<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaginatorBuilder
 */
class PaginatorBuilder
{
    /**
     * @var PaginatorTypeInterface
     */
    private $type;

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
     * @var PageSizeBuilder
     */
    private $pageSizeBuilder;

    /**
     * @var array
     */
    private $formOptions;

    /**
     * PaginatorBuilder constructor.
     *
     * @param PaginatorTypeInterface $type
     */
    public function __construct(PaginatorTypeInterface $type)
    {
        $this->type = $type;
    }

    /**
     * @return PaginatorTypeInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Setup FilterBuilder
     *
     * @param FilterInterface $filter  Filter instance
     * @param array           $options Filter options
     *
     * @return PaginatorBuilder
     */
    public function setFilter(FilterInterface $filter, array $options = array())
    {
        $this->filterBuilder = new FilterBuilder();
        $this->filterQueryProcessors = new QueryProcessorCollection();
        $optionResolver = new OptionsResolver();

        $filter->configureOptions($optionResolver);

        $options = $optionResolver->resolve($options);

        $filter->build($this->filterBuilder, $this->filterQueryProcessors, $options);

        return $this;
    }

    /**
     * Setup SearchBuilder
     *
     * @param SearchInterface $search  Search instance
     * @param array           $options Search options
     *
     * @return PaginatorBuilder
     */
    public function setSearch(SearchInterface $search, array $options = array())
    {
        $this->searchBuilder = new SearchBuilder();
        $this->searchQueryProcessors = new QueryProcessorCollection();
        $optionResolver = new OptionsResolver();

        $search->configureOptions($optionResolver);

        $options = $optionResolver->resolve($options);

        $search->build($this->searchBuilder, $this->searchQueryProcessors, $options);

        return $this;
    }

    /**
     * Setup SortBuilder
     *
     * @param SortInterface $sort Sort instance
     *
     * @return PaginatorBuilder
     */
    public function setSort(SortInterface $sort)
    {
        $this->sortBuilder = new SortBuilder();

        $sort->build($this->sortBuilder);

        return $this;
    }

    /**
     * Setup PageSizeBuilder
     *
     * @param PageSizeInterface $pageSize PageSize instance
     *
     * @return PaginatorBuilder
     */
    public function setPageSize(PageSizeInterface $pageSize)
    {
        $this->pageSizeBuilder = new PageSizeBuilder();

        $pageSize->build($this->pageSizeBuilder);

        return $this;
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
     * @return PageSizeBuilder
     */
    public function getPageSizeBuilder()
    {
        return $this->pageSizeBuilder;
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
     * @return array
     */
    public function getFormOptions()
    {
        return $this->formOptions;
    }

    /**
     * @param array $options
     *
     * @return PaginatorBuilder
     */
    public function setFormOptions(array $options)
    {
        $this->formOptions = $options;

        return $this;
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
    public function hasPageSize()
    {
        return $this->pageSizeBuilder instanceof PageSizeBuilder && $this->pageSizeBuilder->count();
    }
}
