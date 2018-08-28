<?php

namespace Nadia\Bundle\PaginatorBundle\Input;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class InputFactory
{
    /**
     * Generate an Input instance
     *
     * @param Request       $request A Request instance
     * @param FormInterface $form    A FormInterface instance for validating input params
     * @param array         $options Format: {
     *     @var InputKeys        $inputKeys
     *     @var int              $defaultPageSize
     *     @var bool             $sessionEnabled
     *     @var string           $sessionKey
     *     @var SessionInterface $session
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
        $clear = array_key_exists($inputKeys->reset, $params);
        $search = $filter = array();
        $sort = null;
        $pageSize = $options['defaultPageSize'];
        $page = 1;

        if (!$clear) {
            if ($sessionEnabled) {
                $states = $request->getSession()->get($sessionKey, array());
                $states = is_array($states) ? $states : array();
            } else {
                $states = array();
            }

            $this->processParams($states, $params, $inputKeys, $search, $filter, $sort, $pageSize, $page);
        }

        $this->processParamsWithForm($form, $params, $inputKeys, $search, $filter, $sort, $pageSize);

        if ($sessionEnabled) {
            $request->getSession()->set($sessionKey, $params);
        }

        return new Input($filter, $search, $sort, $page, $pageSize);
    }

    /**
     * @param array     $states
     * @param array     $params
     * @param InputKeys $inputKeys
     * @param array     $search
     * @param array     $filter
     * @param string    $sort
     * @param int       $pageSize
     * @param int       $page
     */
    private function processParams(array &$states, array &$params, InputKeys $inputKeys,
                                   array &$search, array &$filter, &$sort, &$pageSize, &$page)
    {
        $search = $this->getValue($inputKeys->search, $states, $params, $search);
        $search = is_array($search) ? $search : array();

        $filter = $this->getValue($inputKeys->filter, $states, $params, $filter);
        $filter = is_array($filter) ? $filter : array();

        $sort = $this->getValue($inputKeys->sort, $states, $params, $sort);

        $pageSize = (int) $this->getValue($inputKeys->pageSize, $states, $params, $pageSize);

        $page = (int) (array_key_exists($inputKeys->page, $params) ? $params[$inputKeys->page] : $page);
    }

    /**
     * @param FormInterface $form
     * @param array         $params
     * @param InputKeys     $inputKeys
     * @param array         $search
     * @param array         $filter
     * @param string        $sort
     * @param int           $pageSize
     */
    private function processParamsWithForm(FormInterface $form, array &$params, InputKeys $inputKeys,
                                           array &$search, array &$filter, &$sort, &$pageSize)
    {
        $params = array(
            $inputKeys->filter => $filter,
            $inputKeys->search => $search,
            $inputKeys->sort   => $sort,
            $inputKeys->pageSize  => $pageSize,
        );
        $params = $form->submit($params)->getData();

        if (array_key_exists($inputKeys->search, $params) && is_array($params[$inputKeys->search])) {
            $this->modifyFilter($params[$inputKeys->search]);

            $search = $params[$inputKeys->search];
        }

        if (array_key_exists($inputKeys->filter, $params) && is_array($params[$inputKeys->filter])) {
            $this->modifyFilter($params[$inputKeys->filter]);

            $filter = $params[$inputKeys->filter];
        }

        $sort = array_key_exists($inputKeys->sort, $params) ? $params[$inputKeys->sort] : $sort;
        $pageSize = array_key_exists($inputKeys->pageSize, $params) ? $params[$inputKeys->pageSize] : $pageSize;
    }

    /**
     * Modify search & filter data
     *
     * 1. Remove empty values
     * 2. Modify search & filter array keys, replace ':' to '.'
     *
     * @param array $data
     */
    private function modifyFilter(array &$data)
    {
        $output = array();

        foreach ($data as $key => $value) {
            if (null === $value || '' === $value) {
                continue;
            }

            $output[str_replace(':', '.', $key)] = $value;
        }

        $data = $output;
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
