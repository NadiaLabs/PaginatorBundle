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
        $clear = array_key_exists($inputKeys->getReset(), $params);
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
        $search = $this->getValue($inputKeys->getSearch(), $states, $params, $search);
        $search = is_array($search) ? $search : array();

        $filter = $this->getValue($inputKeys->getFilter(), $states, $params, $filter);
        $filter = is_array($filter) ? $filter : array();

        $sort = $this->getValue($inputKeys->getSort(), $states, $params, $sort);

        $pageSize = (int) $this->getValue($inputKeys->getPageSize(), $states, $params, $pageSize);

        $page = (int) (array_key_exists($inputKeys->getPage(), $params) ? $params[$inputKeys->getPage()] : $page);
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
        if (0 === count($form)) {
            return;
        }

        $params = array();
        $filterKey = $inputKeys->getFilter();
        $searchKey = $inputKeys->getSearch();
        $sortKey = $inputKeys->getSort();
        $pageSizeKey = $inputKeys->getPageSize();

        if ($form->has($filterKey)) {
            $params[$filterKey] = $filter;
        }
        if ($form->has($searchKey)) {
            $params[$searchKey] = $search;
        }
        if ($form->has($sortKey)) {
            $params[$sortKey] = $sort;
        }
        if ($form->has($pageSizeKey)) {
            $params[$pageSizeKey] = $pageSize;
        }

        $params = $form->submit($params)->getData();

        if ($form->has($filterKey)) {
            /** @var FormInterface $filterForm */
            foreach ($form[$filterKey] as $name => $filterForm) {
                $params[$filterKey][$name] = $filterForm->getViewData();
            }
        }

        if (array_key_exists($searchKey, $params) && is_array($params[$searchKey])) {
            $this->modifyFilter($params[$searchKey]);

            $search = $params[$searchKey];
        }

        if (array_key_exists($filterKey, $params) && is_array($params[$filterKey])) {
            $this->modifyFilter($params[$filterKey]);

            $filter = $params[$filterKey];
        }

        $sort = array_key_exists($sortKey, $params) ? $params[$sortKey] : $sort;
        $pageSize = array_key_exists($pageSizeKey, $params) ? $params[$pageSizeKey] : $pageSize;
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
