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
    private $forms = array();

    /**
     * A QueryCompiler class name
     *
     * @var QueryCompilerInterface
     */
    private $queryCompiler;

    /**
     * Add a filter form parameters
     *
     * @param string $name     Filter name, ex: article.title, article.createdAt, ...
     * @param string $formType Form type class name
     * @param array  $options  Form type options
     *
     * @return $this
     */
    public function add($name, $formType, array $options = array())
    {
        $this->forms[$name] = array(
            'name' => $name,
            'type' => $formType,
            'options' => $options,
        );

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

    /**
     * @return int
     */
    public function count()
    {
        return count($this->forms);
    }

    /**
     * @return QueryCompilerInterface
     */
    public function getQueryCompiler()
    {
        return $this->queryCompiler;
    }

    /**
     * @param QueryCompilerInterface $queryCompiler
     *
     * @return $this
     */
    public function setQueryCompiler(QueryCompilerInterface $queryCompiler)
    {
        $this->queryCompiler = $queryCompiler;

        return $this;
    }
}
