<?php

namespace Nadia\Bundle\PaginatorBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\QueryCompilerInterface;

class SearchQueryCompiler implements QueryCompilerInterface
{
    /**
     * {@inheritdoc}
     */
    public function compile(PaginatorBuilder $builder, $qb, $search)
    {
        /** @var QueryBuilder $qb */
        /** @var array $search */

        $searchBuilder = $builder->getSearchBuilder();
        $callbacks = $this->getCallbacks();

        foreach ($search as $name => $value) {
            if ('' === $value || null === $value || !$searchBuilder->has($name)) {
                continue;
            }

            $params = $searchBuilder->get($name);

            if (!empty($callbacks[$name]) && is_callable([$this, $callbacks[$name]])) {
                call_user_func([$this, $callbacks[$name]], $qb, $params['fields'], $value);
            } else {
                $this->defaultCallback($qb, $params['fields'], $value);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCallbacks()
    {
        return array();
    }

    /**
     * @param QueryBuilder $qb
     * @param string[]     $fields
     * @param mixed        $value
     */
    private function defaultCallback(QueryBuilder $qb, array $fields, $value)
    {
        $criteria = array();

        foreach ($fields as $field) {
            $criteria[] = $qb->expr()->like($field, $qb->expr()->literal('%' . $value . '%'));
        }

        $qb->andWhere(call_user_func_array(array($qb->expr(), 'orX'), $criteria));
    }
}
