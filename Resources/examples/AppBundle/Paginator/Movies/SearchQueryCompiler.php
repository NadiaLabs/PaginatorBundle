<?php

namespace AppBundle\Paginator\Movies;

use Doctrine\ORM\QueryBuilder;
use Nadia\Bundle\PaginatorBundle\Doctrine\ORM\SearchQueryCompiler as BaseSearchQueryCompiler;

class SearchQueryCompiler extends BaseSearchQueryCompiler
{
    public function any(QueryBuilder $qb, $fields, $value)
    {
        // Search with 'LIKE :keywords%' condition
        $criteria1 = $qb->expr()->like('movie.title', $qb->expr()->literal($value . '%'));
        // Search with 'LIKE %:keywords%' condition
        $criteria2 = $qb->expr()->like('movie.description', $qb->expr()->literal('%' . $value . '%'));
        // Search with 'LIKE :keywords%' condition
        $criteria3 = $qb->expr()->like('director.name', $qb->expr()->literal($value . '%'));

        $qb->andWhere($qb->expr()->orX($criteria1, $criteria2, $criteria3));
    }

    /**
     * {@inheritdoc}
     */
    public function getCallbacks()
    {
        return array(
            'movie.any' => 'any',
        );
    }
}
