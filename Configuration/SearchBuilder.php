<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * Class SearchBuilder
 */
class SearchBuilder
{
    /**
     * Search form parameters
     *
     * @var array
     */
    private $forms = array();

    /**
     * Add a search form parameters
     *
     * @param string $name     Search name, ex: article.title, article.createdAt, ...
     * @param array  $fields   Search fields, ex: post.title, user.name
     * @param string $formType Form type class name
     * @param array  $options  Form type options
     *
     * @return $this
     */
    public function add($name, array $fields, $formType, array $options = array())
    {
        if (empty($name) || !count($fields)) {
            return $this;
        }

        $this->forms[$name] = array(
            'name' => $name,
            'type' => $formType,
            'options' => $options,
            'fields' => $fields,
        );

        return $this;
    }

    /**
     * Get a search form parameters
     *
     * @param string $name The search name
     *
     * @return array
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->forms[$name];
        }

        throw new \InvalidArgumentException('The search name "'.$name.'" is not exists!');
    }

    /**
     * Check a search form is exists
     *
     * @param string $name The search name
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
}
