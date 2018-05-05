<?php

namespace NadiaProject\Bundle\PaginatorBundle\Input\InputFactory;

use NadiaProject\Bundle\PaginatorBundle\Input\Input;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HttpFoundationRequestInputFactory
 */
class HttpFoundationRequestInputFactory
{
    /**
     * @param Request $request
     * @param array $options
     * @return Input
     */
    public function factory(Request $request, array $options)
    {
        $parameterBag = $request->isMethod('POST') ? $request->request : $request->query;
        $clear = $parameterBag->has($options['paramNameClear']);
        $states = $request->getSession()->get($options['sessionKey'], []);
        $states = is_array($states) && !$clear ? $states : [];
        $states = new ParameterBag($states);

        $filters = $states->get($options['paramNameFilter'], []);
        $filters = is_array($filters) ? $filters : [];
        $search = (string) $states->get($options['paramNameSearch'], '');
        $sortBy = (string) $states->get($options['paramNameSortBy'], '');
        $sortDirection = (string) $states->get($options['paramNameSortDirection'], 'ASC');
        $page = 1;
        $pageSize = $states->getInt($options['paramNamePageSize'], $options['defaultPageSize']);

        if (!$clear) {
            $filterParameters = $parameterBag->get($options['formName'], []);
            $filterParameters = is_array($filterParameters) ? $filterParameters : [];

            if (array_key_exists($options['paramNameFilter'], $filterParameters)) {
                $filters = $filterParameters[$options['paramNameFilter']];
                $filters = is_array($filters) ? $filters : [];
            }
            if (array_key_exists($options['paramNameSearch'], $filterParameters)) {
                $search = (string) $filterParameters[$options['paramNameSearch']];
            }
            if (array_key_exists($options['paramNamePageSize'], $filterParameters)) {
                $pageSize = (int) $filterParameters[$options['paramNamePageSize']];
            }

            $sortBy = (string) $parameterBag->get($options['paramNameSortBy'], $sortBy);
            $sortDirection = (string) $parameterBag->get($options['paramNameSortDirection'], $sortDirection);
            $page = $parameterBag->getInt($options['paramNamePage'], $page);
        }

        $request->getSession()->set(
            $options['sessionKey'],
            [
                $options['paramNameFilter'] => $filters,
                $options['paramNameSearch'] => $search,
                $options['paramNameSortBy'] => $sortBy,
                $options['paramNameSortDirection'] => $sortDirection,
                $options['paramNamePageSize'] => $pageSize,
            ]
        );

        $sorts = empty($sortBy) ? [] : [$sortBy => $sortDirection];

        return new Input($filters, $search, $sorts, $page, $pageSize);
    }
}
