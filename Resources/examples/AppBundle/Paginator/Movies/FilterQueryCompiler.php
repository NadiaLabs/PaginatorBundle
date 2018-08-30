<?php

namespace AppBundle\Paginator\Movies;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\QueryBuilder;
use Nadia\Bundle\PaginatorBundle\Doctrine\ORM\FilterQueryCompiler as BaseFilterQueryCompiler;

class FilterQueryCompiler extends BaseFilterQueryCompiler
{
    public function releasedAtStart(QueryBuilder $qb, $name, \DateTime $value)
    {
        $qb->andWhere('movie.releasedAt >= :movieReleasedAtStart')
            ->setParameter('movieReleasedAtStart', $value, Type::DATETIME)
        ;
    }

    public function releasedAtEnd(QueryBuilder $qb, $name, \DateTime $value)
    {
        $value->setTime(23, 59, 59);

        $qb->andWhere('movie.releasedAt <= :movieReleasedAtEnd')
            ->setParameter('movieReleasedAtEnd', $value, Type::DATETIME)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getCallbacks()
    {
        return array(
            'movie.releasedAtStart' => 'releasedAtStart',
            'movie.releasedAtEnd' => 'releasedAtEnd',
        );
    }
}
