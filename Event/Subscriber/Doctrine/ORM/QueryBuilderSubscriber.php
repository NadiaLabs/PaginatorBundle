<?php

namespace Nadia\Bundle\PaginatorBundle\Event\Subscriber\Doctrine\ORM;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\QueryCompilerInterface;
use Nadia\Bundle\PaginatorBundle\Configuration\Sort;
use Nadia\Bundle\PaginatorBundle\Event\ItemsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QueryBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * @param ItemsEvent $event
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

        $this->buildFilter($builder, $qb, $input->getFilter());
        $this->buildSearch($builder, $qb, $input->getSearch());
        $this->buildSort($builder, $qb, $input->getSort());

        if (!empty($pageSize)) {
            $qb->setMaxResults($pageSize);
        }
        if (!empty($offset)) {
            $qb->setFirstResult($offset);
        }

        $event->count = $this->count($qb);
        $event->items = $qb->getQuery()->getResult();
    }

    /**
     * @param PaginatorBuilder $builder
     * @param QueryBuilder     $qb
     * @param array            $search
     */
    private function buildSearch(PaginatorBuilder $builder, QueryBuilder $qb, array $search)
    {
        if (!$builder->hasSearch() || empty($search)) {
            return;
        }

        $compiler = $builder->getSearchBuilder()->getQueryCompiler();

        if ($compiler instanceof QueryCompilerInterface) {
            $compiler->compile($builder, $qb, $search);
        }
    }

    /**
     * @param PaginatorBuilder $builder
     * @param QueryBuilder     $qb
     * @param array            $filter
     */
    private function buildFilter(PaginatorBuilder $builder, QueryBuilder $qb, array $filter)
    {
        if (!$builder->hasFilter() || empty($filter)) {
            return;
        }

        $compiler = $builder->getFilterBuilder()->getQueryCompiler();

        if ($compiler instanceof QueryCompilerInterface) {
            $compiler->compile($builder, $qb, $filter);
        }
    }

    /**
     * @param PaginatorBuilder $builder
     * @param QueryBuilder     $qb
     * @param array            $sort
     */
    private function buildSort(PaginatorBuilder $builder, QueryBuilder $qb, array $sort = array())
    {
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
     *
     * @return int
     */
    private function count(QueryBuilder $qb)
    {
        $countQuery = (clone $qb)->resetDQLPart('orderBy')->getQuery();

        $countQuery->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Doctrine\ORM\Tools\Pagination\CountOutputWalker');
        $countQuery->setFirstResult(null);
        $countQuery->setMaxResults(null);
        $countQuery->getEntityManager()->getConfiguration()->addCustomHydrationMode('count', 'Nadia\Bundle\PaginatorBundle\Doctrine\ORM\Query\Hydrator\CountHydrator');

        $countResult = $countQuery->getResult('count');

        return intval(current(current($countResult)));
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
