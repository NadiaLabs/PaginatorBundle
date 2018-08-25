<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * Class PageSizeBuilder
 */
class PageSizeBuilder
{
    /**
     * @var array
     */
    private $pageSizes = array();

    /**
     * PageSize Form options
     *
     * @var array
     */
    private $formOptions = array();

    /**
     * Add a pageSize number, or a list of pageSize numbers
     *
     * @param int|array $pageSizes
     *
     * @return $this
     */
    public function add($pageSizes)
    {
        if (!is_array($pageSizes)) {
            if (!is_numeric($pageSizes)) {
                return $this;
            }

            $pageSizes = [$pageSizes => $pageSizes];
        }

        foreach ($pageSizes as $pageSize) {
            if (!$this->has($pageSize)) {
                $this->pageSizes[$pageSize] = $pageSize;
            }
        }

        ksort($this->pageSizes);

        return $this;
    }

    /**
     * Check a pageSize number is exists
     *
     * @param int $pageSize
     *
     * @return bool
     */
    public function has($pageSize)
    {
        return isset($this->pageSizes[$pageSize]);
    }

    /**
     * Get all pageSize numbers
     *
     * @return array
     */
    public function all()
    {
        return $this->pageSizes;
    }

    /**
     * @return array
     */
    public function getFormOptions()
    {
        return $this->formOptions;
    }

    /**
     * @param array $formOptions
     *
     * @return PageSizeBuilder
     */
    public function setFormOptions(array $formOptions)
    {
        $this->formOptions = $formOptions;

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->pageSizes);
    }
}
