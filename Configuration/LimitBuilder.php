<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * Class LimitBuilder
 */
class LimitBuilder
{
    /**
     * @var array
     */
    private $pageSizes = [];

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
}
