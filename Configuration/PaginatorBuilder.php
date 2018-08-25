<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @var ArrayCollection
     */
    private $filterQueryProcessors;

    /**
     * @var SearchBuilder
     */
    private $searchBuilder;

    /**
     * @var ArrayCollection
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
        $this->filterBuilder = new FilterBuilder();
        $this->filterQueryProcessors = new ArrayCollection();
        $this->searchBuilder = new SearchBuilder();
        $this->searchQueryProcessors = new ArrayCollection();
        $this->sortBuilder = new SortBuilder();
        $this->pageSizeBuilder = new PageSizeBuilder();
    }

    /**
     * @return PaginatorTypeInterface
     */
    public function getType()
    {
        return $this->type;
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
     * @return ArrayCollection
     */
    public function getFilterQueryProcessors()
    {
        return $this->filterQueryProcessors;
    }

    /**
     * @return ArrayCollection
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
