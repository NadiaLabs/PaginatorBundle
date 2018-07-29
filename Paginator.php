<?php

namespace Nadia\Bundle\PaginatorBundle;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountOutputWalker;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Doctrine\ORM\PaginatorQueryBuilder;
use Nadia\Bundle\PaginatorBundle\Doctrine\ORM\Query\Hydrator\CountHydrator;
use Nadia\Bundle\PaginatorBundle\Input\InputInterface;
use Nadia\Bundle\PaginatorBundle\Pagination\Pagination;
use Symfony\Component\Form\FormInterface;

/**
 * Class Paginator
 */
class Paginator
{
    /**
     * @var PaginatorBuilder
     */
    private $builder;
    /**
     * @var FormInterface
     */
    private $form;
    /**
     * @var InputInterface
     */
    private $input;
    /**
     * @var array
     */
    private $options = [];

    /**
     * Paginator constructor.
     *
     * @param PaginatorBuilder $builder
     * @param FormInterface    $form
     * @param InputInterface   $input
     * @param array            $options
     */
    public function __construct(PaginatorBuilder $builder, FormInterface $form, InputInterface $input, array $options)
    {
        $this->builder = $builder;
        $this->form = $form;
        $this->input = $input;
        $this->options = $options;
    }

    /**
     * Do the paginate process and generate Pagination instance
     *
     * @param mixed $target Paginating target, retrieve data from this target instance
     *
     * @return Pagination
     */
    public function paginate(QueryBuilder $target)
    {
        $input = $this->input;

        $queryBuilder = new PaginatorQueryBuilder($this->builder);
        $qb = $queryBuilder->build($target, $input->getFilter(), $input->getSearch(), $input->getSort(), $input->getLimit(), $input->getOffset());

        $count = $this->count($qb);
        $items = $qb->getQuery()->getResult();

        $pagination = new Pagination($this->builder, $this->options, $input, $this->form, $count, $items);

        return $pagination;
    }

    private function count(QueryBuilder $qb)
    {
        $countQuery = (clone $qb)->getQuery();

        $countQuery->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, CountOutputWalker::class);
        $countQuery->setFirstResult(null);
        $countQuery->setMaxResults(null);
        $countQuery->getEntityManager()->getConfiguration()->addCustomHydrationMode('count', CountHydrator::class);

        $countResult = $countQuery->getResult('count');

        return intval(current(current($countResult)));
    }
}
