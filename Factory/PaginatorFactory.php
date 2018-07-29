<?php

namespace Nadia\Bundle\PaginatorBundle\Factory;

use Nadia\Bundle\PaginatorBundle\Configuration\AbstractPaginatorType;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface;
use Nadia\Bundle\PaginatorBundle\Input\Input;
use Nadia\Bundle\PaginatorBundle\Input\InputFactory;
use Nadia\Bundle\PaginatorBundle\Input\QueryParameterDefinition;
use Nadia\Bundle\PaginatorBundle\Paginator;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaginatorFactory
 */
class PaginatorFactory
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param string $type
     * @param array $options
     *
     * @return Paginator
     */
    public function createPaginator($type, array $options = [])
    {
        $type = $this->getType($type);
        $options = $this->resolveOptions($type, $options);
        $input = $this->getInputFactory()->factory($options);

        $builder = new PaginatorBuilder();

        $type->build($builder, $options);

        $form = $this->createForm($builder, $input, $options);

        return new Paginator($builder, $form, $input, $options);
    }

    /**
     * @param PaginatorBuilder $builder
     * @param Input            $input
     * @param array            $options
     *
     * @return FormInterface
     */
    private function createForm(PaginatorBuilder $builder, Input $input, array $options)
    {
        /** @var QueryParameterDefinition $queryParamDef */
        $queryParamDef = $options['queryParams'];
        $data = [
            $queryParamDef->filter => $input->getFilter(),
            $queryParamDef->search => $input->getSearch(),
            $queryParamDef->sort   => $input->getSort(),
            $queryParamDef->limit  => $input->getLimit(),
        ];
        $form = $this->formFactory->createNamed(null, FormType::class, null, ['csrf_protection' => false]);

        if ($builder->hasFilter()) {
            $filterForm = $this->formFactory->createNamed($queryParamDef->filter, FormType::class, null, ['auto_initialize' => false]);

            foreach ($builder->getFilterBuilder()->all() as $filter) {
                $filterOptions = array_merge(['required' => false], $filter['options']);

                $filterForm->add($filter['name'], $filter['type'], $filterOptions);
            }

            $form->add($filterForm);
        }

        if ($builder->hasSearch()) {
            $searchForm = $this->formFactory->createNamed($queryParamDef->search, FormType::class, null, ['auto_initialize' => false]);

            foreach ($builder->getSearchBuilder()->all() as $search) {
                $searchOptions = array_merge(['required' => false], $search['options']);

                $searchForm->add($search['name'], $search['type'], $searchOptions);
            }

            $form->add($searchForm);
        }

        if ($builder->hasSort()) {
            $form->add($queryParamDef->sort, ChoiceType::class, ['choices' => $builder->getSortBuilder()->all()]);
        }

        if ($builder->hasLimit()) {
            $form->add($queryParamDef->limit, ChoiceType::class, ['choices' => $builder->getLimitBuilder()->all()]);
        }

        $form->submit($data);

        return $form;
    }

    /**
     * @param string $type Paginator type class
     *
     * @return PaginatorTypeInterface
     */
    private function getType($type)
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException('Could not load type "'.$type.'": class does not exist.');
        }
        if (!is_subclass_of($type, 'Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface')) {
            throw new \InvalidArgumentException('Could not load type "'.$type.'": class does not implement "Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface".');
        }

        return new $type();
    }

    /**
     * @param PaginatorTypeInterface $type
     * @param array                  $options
     *
     * @return array
     */
    private function resolveOptions(PaginatorTypeInterface $type, array $options)
    {
        $resolver = new OptionsResolver();

        if ($type instanceof AbstractPaginatorType) {
            $type->defaultConfigureOptions($resolver);
        }

        $type->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    /**
     * @return InputFactory
     */
    private function getInputFactory()
    {
        return new InputFactory();
    }
}
