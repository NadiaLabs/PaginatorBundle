<?php

namespace Nadia\Bundle\PaginatorBundle\Input;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class InputFactory
 */
class InputFactory
{
    /**
     * Generate an Input instance
     *
     * @param Request       $request A Request instance
     * @param FormInterface $form    A FormInterface instance for validating input params
     * @param array         $options Format: {
     *     @var $inputKeys       InputKeys
     *     @var $defaultPageSize int
     *     @var $sessionEnabled  bool
     *     @var $sessionKey      string
     *     @var $session         SessionInterface
     * }
     *
     * @return Input
     */
    public function create(Request $request, FormInterface $form, array $options)
    {
        $params = $request->isMethod('POST') ? array_merge($request->query->all(), $request->request->all()) : $request->query->all();
        /** @var InputKeys $inputKeys */
        $inputKeys = $options['inputKeys'];
        $sessionKey = $options['sessionKey'];
        $sessionEnabled = $options['sessionEnabled'];
        $clear = array_key_exists($inputKeys->clear, $params);
        $filter = $search = array();
        $sort = null;
        $limit = $options['defaultPageSize'];
        $page = 1;

        if (!$clear) {
            if ($sessionEnabled) {
                $states = $request->getSession()->get($sessionKey, array());
                $states = is_array($states) ? $states : array();
            } else {
                $states = array();
            }

            $this->processParams($states, $params, $inputKeys, $filter, $search, $sort, $limit, $page);
        }

        $this->processParamsWithForm($form, $params, $inputKeys, $filter, $search, $sort, $limit);

        if ($sessionEnabled) {
            $request->getSession()->set($sessionKey, $params);
        }

        return new Input($filter, $search, $sort, $page, $limit);
    }

    /**
     * @param array     $states
     * @param array     $params
     * @param InputKeys $inputKeys
     * @param array     $filter
     * @param array     $search
     * @param string    $sort
     * @param int       $limit
     * @param int       $page
     */
    private function processParams(array &$states, array &$params, InputKeys $inputKeys,
                                   array &$filter, array &$search, &$sort, &$limit, &$page)
    {
        $filter = $this->getValue($inputKeys->filter, $states, $params, $filter);
        $filter = is_array($filter) ? $filter : array();

        $search = $this->getValue($inputKeys->search, $states, $params, $search);
        $search = is_array($search) ? $search : array();

        $sort = $this->getValue($inputKeys->sort, $states, $params, $sort);

        $limit = (int) $this->getValue($inputKeys->limit, $states, $params, $limit);

        $page = (int) (array_key_exists($inputKeys->page, $params) ? $params[$inputKeys->page] : $page);
    }

    /**
     * @param FormInterface $form
     * @param array         $params
     * @param InputKeys     $inputKeys
     * @param array         $filter
     * @param array         $search
     * @param string        $sort
     * @param int           $limit
     */
    private function processParamsWithForm(FormInterface $form, array &$params, InputKeys $inputKeys,
                                           array &$filter, array &$search, &$sort, &$limit)
    {
        $params = array(
            $inputKeys->filter => $filter,
            $inputKeys->search => $search,
            $inputKeys->sort   => $sort,
            $inputKeys->limit  => $limit,
        );
        $params = $form->submit($params)->getData();

        if (array_key_exists($inputKeys->filter, $params) && is_array($params[$inputKeys->filter])) {
            $this->modifyFilter($params[$inputKeys->filter]);

            $filter = $params[$inputKeys->filter];
        }

        if (array_key_exists($inputKeys->search, $params) && is_array($params[$inputKeys->search])) {
            $this->modifyFilter($params[$inputKeys->search]);

            $search = $params[$inputKeys->search];
        }

        $sort = array_key_exists($inputKeys->sort, $params) ? $params[$inputKeys->sort] : $sort;
        $limit = array_key_exists($inputKeys->limit, $params) ? $params[$inputKeys->limit] : $limit;
    }

    /**
     * Modify filter data
     *
     * 1. Remove empty values
     * 2. Replace ':' to '.' in filter & search array keys
     *
     * @param array $filter
     */
    private function modifyFilter(array &$filter)
    {
        $output = array();

        foreach ($filter as $key => $value) {
            if (null === $value || '' === $value) {
                continue;
            }

            $output[str_replace(':', '.', $key)] = $value;
        }

        $filter = $output;
    }

    /**
     * Get input value by key
     *
     * @param string $key
     * @param array  $states
     * @param array  $params
     * @param mixed  $default
     *
     * @return mixed
     */
    private function getValue($key, array &$states, array &$params, $default)
    {
        $value = array_key_exists($key, $states) ? $states[$key] : $default;
        $value = array_key_exists($key, $params) ? $params[$key] : $value;

        return $value;
    }
}
