<?php

namespace Nadia\Bundle\PaginatorBundle\Input;

/**
 * Class InputFactory
 */
class InputFactory
{
    /**
     * Generate an Input instance
     *
     * @param array $options
     *
     * @return Input
     */
    public function factory(array $options)
    {
        /** @var QueryParameterDefinition $queryParamDef */
        $queryParamDef = $options['queryParams'];
        $sessionKey = $options['sessionKey'];
        $params = array_merge($_GET, $_POST);
        $clear = array_key_exists($queryParamDef->clear, $params);
        $filter = $search = [];
        $sort = null;
        $limit = $options['defaultLimit'];
        $page = 1;

        if (!$clear) {
            if (isset($_SESSION) && $options['sessionEnabled']) {
                $states = array_key_exists($sessionKey, $_SESSION) ? $_SESSION[$sessionKey] : [];
                $states = (is_array($states) && !$clear) ? $states : [];
            } else {
                $states = [];
            }

            $filter = array_key_exists($queryParamDef->filter, $states) ? $states[$queryParamDef->filter] : $filter;
            $filter = array_key_exists($queryParamDef->filter, $params) ? $params[$queryParamDef->filter] : $filter;
            $filter = is_array($filter) ? $filter : [];
            $search = array_key_exists($queryParamDef->search, $states) ? $states[$queryParamDef->search] : $search;
            $search = array_key_exists($queryParamDef->search, $params) ? $params[$queryParamDef->search] : $search;
            $search = is_array($search) ? $search : [];
            $sort = array_key_exists($queryParamDef->sort, $states) ? $states[$queryParamDef->sort] : $sort;
            $sort = array_key_exists($queryParamDef->sort, $params) ? $params[$queryParamDef->sort] : $sort;
            $limit = (int) (array_key_exists($queryParamDef->limit, $states) ? $states[$queryParamDef->limit] : $limit);
            $limit = (int) (array_key_exists($queryParamDef->limit, $params) ? $params[$queryParamDef->limit] : $limit);
            $page = (int) (array_key_exists($queryParamDef->page, $params) ? $params[$queryParamDef->page] : $page);
        }

        if (isset($_SESSION) && $options['sessionEnabled']) {
            $_SESSION[$sessionKey] = [
                $queryParamDef->filter => $filter,
                $queryParamDef->search => $search,
                $queryParamDef->sort => $sort,
                $queryParamDef->limit => $limit,
            ];
        }

        $this->replaceKeys($filter, $search);

        return new Input($filter, $search, $sort, $page, $limit);
    }

    /**
     * Replace ':' to '.' in filter & search array keys
     *
     * @param array $filter
     * @param array $search
     */
    private function replaceKeys(array &$filter, array &$search)
    {
        $replaceKeys = function(array $input) {
            $output = [];

            foreach ($input as $key => $value) {
                $output[str_replace(':', '.', $key)] = $value;
            }

            return $output;
        };

        $filter = $replaceKeys($filter);
        $search = $replaceKeys($search);
    }
}
