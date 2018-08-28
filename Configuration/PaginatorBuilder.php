<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

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
     * @var array
     */
    private $typeOptions;

    /**
     * @var SearchBuilder
     */
    private $searchBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

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
    private $formOptions = array();

    /**
     * @var bool
     */
    private $locked = false;

    /**
     * PaginatorBuilder constructor.
     *
     * Parameters @see \Nadia\Bundle\PaginatorBundle\Factory\PaginatorFactory::create
     *
     * @param PaginatorTypeInterface $type
     * @param array                  $typeOptions
     */
    public function __construct(PaginatorTypeInterface $type, array $typeOptions)
    {
        $this->type = $type;
        $this->typeOptions = $typeOptions;
    }

    /**
     * Build PaginatorBuilder properties (Only build once)
     */
    public function build()
    {
        if ($this->locked) {
            return;
        }

        $this->searchBuilder = $searchBuilder = new SearchBuilder();
        $this->filterBuilder = $filterBuilder = new FilterBuilder();
        $this->sortBuilder =  $sortBuilder = new SortBuilder();
        $this->pageSizeBuilder = $pageSizeBuilder = new PageSizeBuilder();

        $this->type->buildSearch($searchBuilder, $this->typeOptions);
        $this->type->buildFilter($filterBuilder, $this->typeOptions);
        $this->type->buildSort($sortBuilder, $this->typeOptions);
        $this->type->buildPageSize($pageSizeBuilder, $this->typeOptions);

        $this->locked = true;
    }

    /**
     * @return PaginatorTypeInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @see \Nadia\Bundle\PaginatorBundle\Factory\PaginatorFactory::create
     *
     * @return array
     */
    public function getTypeOptions()
    {
        return $this->typeOptions;
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getTypeOption($name, $default = null)
    {
        return array_key_exists($name, $this->typeOptions) ? $this->typeOptions[$name] : $default;
    }

    /**
     * @return SearchBuilder
     */
    public function getSearchBuilder()
    {
        return $this->searchBuilder;
    }

    /**
     * @return FilterBuilder
     */
    public function getFilterBuilder()
    {
        return $this->filterBuilder;
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
     * @return array
     */
    public function getFormOptions()
    {
        return $this->formOptions;
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
