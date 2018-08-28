<?php

namespace Nadia\Bundle\PaginatorBundle\DependencyInjection\Container;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface;
use Psr\Container\ContainerInterface;

class PaginatorTypeContainer implements ContainerInterface
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
    public function get($name)
    {
        if ($this->typeContainer->has($name)) {
            return $this->typeContainer->get($name);
        }

        if (!class_exists($name)) {
            throw new \InvalidArgumentException('Could not load type "'.$name.'": class does not exist.');
        }

        if (!is_subclass_of($name, 'Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface')) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Could not load type "%s": class does not implement "%s".',
                    $name,
                    'Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface'
                )
            );
        }

        return new $name();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return $this->typeContainer->has($name)
            || (class_exists($name) && is_subclass_of($name, 'Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface'))
        ;
    }
}
