<?php

namespace Nadia\Bundle\PaginatorBundle\Pagination;

/**
 * Class Pagination
 */
class Pagination extends AbstractPagination
{
    /**
     * @var string
     */
    private $route;
    /**
     * @var array
     */
    private $routeParams = array();

    /**
     * Get current route name
     *
     * @return string
     */
    public function getCurrentRoute()
    {
        return $this->route;
    }

    /**
     * Set current route name
     *
     * @param string $route
     *
     * @return $this
     */
    public function setCurrentRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get current route parameters
     *
     * @return array
     */
    public function getCurrentRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * Set current route parameters
     *
     * @param array $params
     *
     * @return $this
     */
    public function setCurrentRouteParams(array $params)
    {
        $this->routeParams = $params;

        return $this;
    }
}
