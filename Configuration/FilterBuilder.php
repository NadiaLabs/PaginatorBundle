<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * Class FilterBuilder
 */
class FilterBuilder
{
    /**
     * Filter form parameters
     *
     * @var array
     */
    private $forms = [];

    /**
     * Add a filter form parameters
     *
     * @param string $name     Filter name
     * @param string $formType Form type class name
     * @param array  $options  Form type options
     *
     * @return $this
     */
    public function add($name, $formType, array $options = [])
    {
        $parts = explode('.', $name);

        if (2 !== count($parts)) {
            return $this;
        }

        $this->forms[$name] = [
            'alias' => $parts[0],
            'name' => $parts[1],
            'type' => $formType,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * @param string $name The filter name
     *
     * @return array
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->forms[$name];
        }

        throw new \InvalidArgumentException('The filter name "'.$name.'" is not exists!');
    }

    /**
     * Check a filter form is exists
     *
     * @param string $name The filter name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->forms);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->forms;
    }
}
