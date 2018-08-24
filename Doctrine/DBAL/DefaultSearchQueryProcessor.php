<?php

namespace Nadia\Bundle\PaginatorBundle\Doctrine\DBAL;

use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class DefaultSearchQueryProcessor
 */
class DefaultSearchQueryProcessor
{
    /**
     * The default QueryProcessor for search configurations
     *
     * @param QueryBuilder $qb      The DBAL QueryBuilder instance
     * @param array        $fields  Search fields
     * @param string       $value   Search keywords
     *
     * @return void
     */
    public static function process(QueryBuilder $qb, array $fields, $value)
    {
        $criteria = array();

        foreach ($fields as $field) {
            $criteria[] = $qb->expr()->like($field, $qb->expr()->literal('%' . $value . '%'));
        }

        $qb->andWhere(call_user_func_array(array($qb->expr(), 'orX'), $criteria));
    }
}
