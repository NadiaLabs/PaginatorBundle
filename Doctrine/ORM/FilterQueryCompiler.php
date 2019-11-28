<?php

namespace Nadia\Bundle\PaginatorBundle\Doctrine\ORM;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\QueryCompilerInterface;
use Nadia\Bundle\PaginatorBundle\Input\Input;

class FilterQueryCompiler implements QueryCompilerInterface
{
    /**
     * {@inheritdoc}
     */
    public function compile($qb, Input $input, PaginatorBuilder $builder)
    {
        /** @var QueryBuilder $qb */

        $filter = $input->getFilter();
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
    protected function defaultCallback(QueryBuilder $qb, $fieldName, $value)
    {
        $parameterName = str_replace('.', '_', $fieldName);

        if (is_array($value)) {
            $qb->andWhere(sprintf('%s IN (:%s)', $fieldName, $parameterName));
            $qb->setParameter($parameterName, $value, Connection::PARAM_STR_ARRAY);
        } else {
            $qb->andWhere(sprintf('%s = :%s', $fieldName, $parameterName));
            $qb->setParameter($parameterName, $value);
        }
    }
}
