<?php

namespace Nadia\Bundle\PaginatorBundle\Event\Subscriber\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\QueryCompilerInterface;
use Nadia\Bundle\PaginatorBundle\Configuration\Sort;
use Nadia\Bundle\PaginatorBundle\Doctrine\ORM\FilterQueryCompiler;
use Nadia\Bundle\PaginatorBundle\Doctrine\ORM\SearchQueryCompiler;
use Nadia\Bundle\PaginatorBundle\Event\ItemsEvent;
use Nadia\Bundle\PaginatorBundle\Input\Input;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QueryBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * @param ItemsEvent $event
     *
     * @throws Exception
     */
    public function items(ItemsEvent $event)
    {
        if (!$event->target instanceof QueryBuilder) {
            return;
        }

        $qb = $event->target;
        $builder = $event->getBuilder();
        $input = $event->getInput();

        $pageSize = $input->getPageSize();
        $offset = $input->getOffset();

        $this->buildSearch($qb, $input, $builder);
        $this->buildFilter($qb, $input, $builder);
        $this->buildSort($qb, $input, $builder);

        if (!empty($pageSize)) {
            $qb->setMaxResults($pageSize);
        }
        if (!empty($offset)) {
            $qb->setFirstResult($offset);
        }

        $event->count = $this->count($qb, $event->getBuilder());
        $event->items = iterator_to_array((new Paginator($qb->getQuery()))->getIterator());
    }

    /**
     * @param QueryBuilder     $qb
     * @param Input            $input
     * @param PaginatorBuilder $builder
     */
    private function buildSearch(QueryBuilder $qb, Input $input, PaginatorBuilder $builder)
    {
        $search = $input->getSearch();

        if (!$builder->hasSearch() || empty($search)) {
            return;
        }

        $compiler = $builder->getSearchBuilder()->getQueryCompiler();

        if (!$compiler instanceof QueryCompilerInterface) {
            $compiler = new SearchQueryCompiler();
        }

        $compiler->compile($qb, $input, $builder);
    }

    /**
     * @param QueryBuilder     $qb
     * @param Input            $input
     * @param PaginatorBuilder $builder
     */
    private function buildFilter(QueryBuilder $qb, Input $input, PaginatorBuilder $builder)
    {
        $filter = $input->getFilter();

        if (!$builder->hasFilter() || empty($filter)) {
            return;
        }

        $compiler = $builder->getFilterBuilder()->getQueryCompiler();

        if (!$compiler instanceof QueryCompilerInterface) {
            $compiler = new FilterQueryCompiler();
        }

        $compiler->compile($qb, $input, $builder);
    }

    /**
     * @param QueryBuilder     $qb
     * @param Input            $input
     * @param PaginatorBuilder $builder
     */
    private function buildSort(QueryBuilder $qb, Input $input, PaginatorBuilder $builder)
    {
        $sort = $input->getSort();

        if (!$builder->hasSort() || empty($sort) || empty($sort['key']) || empty($sort['direction'])) {
            return;
        }

        $sort = $builder->getSortBuilder()->get($sort['key'], $sort['direction']);

        $orderBys = array_map(function($v) {
            $parts = explode(' ', $v, 2);

            if (1 === count($parts)) {
                // Default order direction
                $parts[] = Sort::ASC;
            }

            return array(
                'fieldName' => $parts[0],
                'direction' => strtoupper($parts[1]),
            );
        }, explode(',', $sort['statement']));

        foreach ($orderBys as $orderBy) {
            $qb->addOrderBy($orderBy['fieldName'], $orderBy['direction']);
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param PaginatorBuilder $builder
     *
     * @return int
     */
    private function count(QueryBuilder $qb, PaginatorBuilder $builder)
    {
        $countQuery = (clone $qb)->resetDQLPart('orderBy')->getQuery();

        if ($builder->getTypeOption('enableResultCache')) {
            $countQuery->enableResultCache($builder->getTypeOption('resultCacheLifetime', null));
        }

        return (new Paginator($countQuery))->count();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'nadia_paginator.items' => array('items', 10)
        );
    }
}
