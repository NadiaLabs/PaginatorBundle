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

    /**
     * PaginatorFactory constructor.
     *
     * @param FormFactoryInterface $formFactory
     */
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
                $fieldName = str_replace('.', ':', $filter['name']);

                $filterForm->add($fieldName, $filter['type'], $filterOptions);
            }

            $form->add($filterForm);
        }

        if ($builder->hasSearch()) {
            $searchForm = $this->formFactory->createNamed($queryParamDef->search, FormType::class, null, ['auto_initialize' => false]);

            foreach ($builder->getSearchBuilder()->all() as $search) {
                $searchOptions = array_merge(['required' => false], $search['options']);
                $fieldName = str_replace('.', ':', $search['name']);

                $searchForm->add($fieldName, $search['type'], $searchOptions);
            }

            $form->add($searchForm);
        }

        if ($builder->hasSort()) {
            $formOptions = [
                'required' => false,
                'choices' => $builder->getSortBuilder()->all(),
                'placeholder' => '',
            ];
            $form->add($queryParamDef->sort, ChoiceType::class, $formOptions);
        }

        if ($builder->hasLimit()) {
            $formOptions = [
                'required' => false,
                'choices' => $builder->getLimitBuilder()->all(),
            ];

            $form->add($queryParamDef->limit, ChoiceType::class, $formOptions);
        }

        $form->submit($data);

        return $form;
    }

    /**
     * @param string $typeClass Paginator type class
     *
     * @return PaginatorTypeInterface
     */
    private function getType($typeClass)
    {
        if (!class_exists($typeClass)) {
            throw new \InvalidArgumentException('Could not load type "'.$typeClass.'": class does not exist.');
        }
        if (!is_subclass_of($typeClass, 'Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface')) {
            throw new \InvalidArgumentException('Could not load type "'.$typeClass.'": class does not implement "Nadia\Bundle\PaginatorBundle\Configuration\PaginatorTypeInterface".');
        }

        return new $typeClass();
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
