<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

/**
 * Class SortBuilder
 */
class SortBuilder
{
    /**
     * Sort information
     *
     * Format: array(
     *     'foo field key' => array(
     *         'ASC' => array (
     *             'statement' => 'foo ASC',
     *             'title' => 'foo ASC sort title'
     *             'direction' => 'ASC',
     *         ),
     *         'DESC' => array (
     *             'statement' => 'foo DESC',
     *             'title' => 'foo ASC sort title'
     *             'direction' => 'DESC',
     *         ),
     *     ),
     *     'foobar field key' => array(
     *         'ASC' => array (
     *             'statement' => 'foobar ASC',
     *             'title' => 'foobar ASC sort title'
     *             'direction' => 'ASC',
     *         ),
     *         'DESC' => array (
     *             'statement' => 'foobar DESC',
     *             'title' => 'foobar ASC sort title'
     *             'direction' => 'DESC',
     *         ),
     *     ),
     *     ...
     * )
     *
     * @var array
     */
    private $sorts = array();

    /**
     * Sort choices
     *
     * The value of choices is the array key of `$this->sorts`
     *
     * Format: array(
     *     'foo ASC sort title' => 'foo ASC',
     *     'foo DESC sort title' => 'foo DESC',
     *     'foobar ASC sort title' => 'foobar ASC',
     *     'foobar DESC sort title' => 'foobar DESC',
     * )
     *
     * @var array
     */
    private $choices = array();

    /**
     * Sort Form options
     *
     * @var array
     */
    private $formOptions = array();

    /**
     * Add a sort information
     *
     * A sort information can only have two directions (SortInterface::ASC and SortInterface::DESC)
     *
     * @param string $key       Sort field key
     * @param string $direction SortInterface::ASC or SortInterface::DESC
     * @param string $statement The sort statement, ex: 'foo', 'foo ASC', 'foo.abc ASC, bar.xzy DESC'
     * @param string $title     The sort title
     *
     * @return $this
     */
    public function add($key, $direction, $statement, $title = '')
    {
        if (!array_key_exists($key, $this->sorts)) {
            $this->sorts[$key] = array();
        }

        if (!in_array($direction, array(SortInterface::ASC, SortInterface::DESC))) {
            $direction = SortInterface::ASC;
        }

        if (!array_key_exists($direction, $this->sorts[$key])) {
            $this->sorts[$key][$direction] = array();
        }

        $this->sorts[$key][$direction] = compact('statement', 'title', 'direction');
        $this->choices[$title] = $this->getMapKey($key, $direction);

        return $this;
    }

    /**
     * Get a sort information
     *
     * @param string $key       Sort field key
     * @param string $direction SortInterface::ASC or SortInterface::DESC
     *
     * @return array Format: array('statement' => 'foo ASC sort title', 'title' => 'foo ASC sort title', 'direction' => 'ASC')
     */
    public function get($key, $direction = SortInterface::ASC)
    {
        if (empty($this->sorts[$key])) {
            throw new \InvalidArgumentException('The sort key "'.$key.'" is not exists!');
        }
        if (empty($this->sorts[$key][$direction])) {
            throw new \InvalidArgumentException('The sort direction "'.$direction.'" is not exists!');
        }

        return $this->sorts[$key][$direction];
    }

    /**
     * Get a sort information with reversed direction
     *
     * @param string $key       Sort field key
     * @param string $direction SortInterface::ASC or SortInterface::DESC
     *
     * @return array Format: array('statement' => 'foo ASC sort title', 'title' => 'foo ASC sort title', 'direction' => 'ASC')
     */
    public function getReversed($key, $direction = SortInterface::ASC)
    {
        $direction = $direction !== SortInterface::DESC ? SortInterface::DESC : SortInterface::ASC;

        return $this->get($key, $direction);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->sorts;
    }

    /**
     * @return array
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->sorts);
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
     * @return SortBuilder
     */
    public function setFormOptions(array $formOptions)
    {
        $this->formOptions = $formOptions;

        return $this;
    }

    /**
     * Get the array key of `$this->sorts`
     *
     * @param string $key       Sort field key
     * @param string $direction SortInterface::ASC or SortInterface::DESC
     *
     * @return string
     */
    private function getMapKey($key, $direction)
    {
        return $key . ' ' . $direction;
    }
}
