<?php

namespace Nadia\Bundle\PaginatorBundle\Doctrine\ORM;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\QueryCompilerInterface;

abstract class AbstractFilterQueryCompiler implements QueryCompilerInterface
{
    /**
     * {@inheritdoc}
     */
    public function compile(PaginatorBuilder $builder, $qb, $filter)
    {
        /** @var QueryBuilder $qb */
        /** @var array $filter */

        $callbacks = $this->getCallbacks();

        foreach ($filter as $fieldName => $value) {
            if ('' === $value || null === $value) {
                continue;
            }

            if (!empty($callbacks[$fieldName]) && is_callable([$this, $callbacks[$fieldName]])) {
                call_user_func([$this, $callbacks[$fieldName]], $qb, $fieldName, $value);
            } else {
                $this->defaultCallback($qb, $fieldName, $value);
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
     * @param string       $fieldName
     * @param mixed        $value
     */
    private function defaultCallback(QueryBuilder $qb, $fieldName, $value)
    {
        if (is_array($value)) {
            $qb->andWhere(sprintf('%s IN (:%s)', $fieldName, $fieldName));
            $qb->setParameter($fieldName, $value, Connection::PARAM_STR_ARRAY);
        } else {
            $qb->andWhere(sprintf('%s = :%s', $fieldName, $fieldName));
            $qb->setParameter($fieldName, $value);
        }
    }
}
