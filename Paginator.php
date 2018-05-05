<?php

namespace NadiaProject\Bundle\PaginatorBundle;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountOutputWalker;
use NadiaProject\Bundle\PaginatorBundle\Builder\PaginatorBuilder;
use NadiaProject\Bundle\PaginatorBundle\Builder\PaginatorFormBuilder;
use NadiaProject\Bundle\PaginatorBundle\Doctrine\ORM\Query\Hydrator\CountHydrator;
use NadiaProject\Bundle\PaginatorBundle\Input\InputFactory\HttpFoundationRequestInputFactory;
use NadiaProject\Bundle\PaginatorBundle\QueryBuilder\ORMQueryBuilder;
use NadiaProject\Bundle\PaginatorBundle\Type\PaginatorTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Paginator
 */
class Paginator
{
    /**
     * @var PaginatorFormBuilder
     */
    private $formBuilder;

    public function __construct(PaginatorFormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    /**
     * Do the paginate process and generate Pagination instance
     *
     * @param mixed $target Paginating target, retrieve data from this target instance
     * @param mixed $request Request data
     * @param PaginatorTypeInterface $type PaginatorType instance
     * @param array $typeOptions PaginatorType options
     *
     * @return Pagination
     */
    public function paginate($target, $request, PaginatorTypeInterface $type, array $typeOptions = [])
    {
        if (!($target instanceof QueryBuilder)) {
            throw new \InvalidArgumentException('Unsupported target instance. Currently only support Doctrine\ORM\QueryBuilder.');
        }
        if (!($request instanceof Request)) {
            throw new \InvalidArgumentException('Unsupported request instance. Currently only support Symfony\Component\HttpFoundation\Request.');
        }

        $typeOptions = $this->resolveTypeOptions($type, $typeOptions);
        $inputFactory = $this->getInputFactory();
        $input = $inputFactory->factory($request, $typeOptions);

        $paginatorBuilder = new PaginatorBuilder();

        $type->buildPaginator($paginatorBuilder, $typeOptions);
        $paginatorBuilder->validateInput($input);

        $form = $this->formBuilder->build($paginatorBuilder, $input, $typeOptions);

        $offset = ($input->getPage() - 1) * $input->getPageSize();
        $queryBuilder = new ORMQueryBuilder($paginatorBuilder);
        $qb = $queryBuilder->build($target, $input->getSearch(), $input->getFilters(), $input->getSorts(), $input->getPageSize(), $offset);
        $countQuery = (clone $qb)->getQuery();

        $countQuery->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, CountOutputWalker::class);
        $countQuery->setFirstResult(null);
        $countQuery->setMaxResults(null);
        $countQuery->getEntityManager()->getConfiguration()->addCustomHydrationMode('count', CountHydrator::class);

        $countResult = $countQuery->getResult('count');
        $count = intval(current(current($countResult)));
        $items = $qb->getQuery()->getResult();

        $pagination = new Pagination(
            $paginatorBuilder, $typeOptions, $input, $request, $count, $items, $form
        );

        return $pagination;
    }

    /**
     * Process input data
     *
     * @param $request
     * @param PaginatorTypeInterface $type
     * @param array $typeOptions
     *
     * @return Input\Input
     */
    public function resolveInputSession($request, PaginatorTypeInterface $type, array $typeOptions = [])
    {
        $typeOptions = $this->resolveTypeOptions($type, $typeOptions);
        $inputFactory = $this->getInputFactory();

        return $inputFactory->factory($request, $typeOptions);
    }

    /**
     * Resolve PaginatorType options
     *
     * @param PaginatorTypeInterface $type PaginatorType instance
     * @param array $options PaginatorType options
     *
     * @return array Resolved PaginatorType options
     */
    private function resolveTypeOptions(PaginatorTypeInterface $type, array $options = [])
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired([
            'paramNameFilter',
            'paramNameSearch',
            'paramNameSortBy',
            'paramNameSortDirection',
            'paramNamePage',
            'paramNamePageSize',
            'defaultPageSize',
            'paramNameClear',
        ]);

        $resolver->setDefaults([
            'formName' => 'form',
            'paramNameFilter' => 'filter',
            'paramNameSearch' => 'search',
            'paramNameSortBy' => 'sort_by',
            'paramNameSortDirection' => 'sort_direction',
            'paramNamePage' => 'page',
            'paramNamePageSize' => 'page_size',
            'defaultPageSize' => 10,
            'paramNameClear' => '_clear_all_parameters',
            'sessionKey' => 'nadia.paginator.params.' . hash('md5', get_class($type)),
            'templatePagination' => '@NadiaPaginator/default/pagination.html.twig',
            'templateFilters' => '@NadiaPaginator/default/filters.html.twig',
            'templateSort' => '@NadiaPaginator/default/sort.html.twig',
        ]);

        $type->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    /**
     * @return HttpFoundationRequestInputFactory
     */
    public function getInputFactory()
    {
        return new HttpFoundationRequestInputFactory();
    }
}
