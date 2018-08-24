<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Psr\Container\ContainerInterface;

/**
 * Class DependencyInjectionPaginatorTypeLoader
 */
class DependencyInjectionPaginatorTypeLoader
{
    /**
     * @var ContainerInterface
     */
    private $typeContainer;

    /**
     * @param ContainerInterface $typeContainer
     */
    public function __construct(ContainerInterface $typeContainer)
    {
        $this->typeContainer = $typeContainer;
    }

    /**
     * @param string $name
     *
     * @return PaginatorTypeInterface
     */
    public function getType($name)
    {
        if (!$this->typeContainer->has($name)) {
            throw new \InvalidArgumentException(sprintf('The paginator type "%s" is not registered in the service container.', $name));
        }

        return $this->typeContainer->get($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasType($name)
    {
        return $this->typeContainer->has($name);
    }
}
