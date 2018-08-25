<?php

namespace Nadia\Bundle\PaginatorBundle\Factory;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\InputKeys;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class PaginatorFormFactory
 */
class PaginatorFormFactory
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
     * Create a FormInterface instance for submitting and rendering
     *
     * @param PaginatorBuilder $builder
     * @param array            $options
     * @return FormInterface
     */
    public function create(PaginatorBuilder $builder, array &$options)
    {
        $formOptions = array(
            'csrf_protection' => false,
            'method' => $options['sessionEnabled'] ? 'POST' : 'GET'
        );
        $formOptions = array_merge($formOptions, $builder->getFormOptions());
        $form = $this->formFactory->createNamed(null, FormType::class, null, $formOptions);

        /** @var InputKeys $inputKeys */
        $inputKeys = $options['inputKeys'];

        if ($builder->hasFilter()) {
            $filterForm = $this->formFactory->createNamed($inputKeys->filter, FormType::class, null, array('auto_initialize' => false));

            foreach ($builder->getFilterBuilder()->all() as $filter) {
                $fieldName = str_replace('.', ':', $filter['name']);
                $filterOptions = array(
                    'label' => ucfirst($fieldName),
                    'required' => false,
                );
                $filterOptions = array_merge($filterOptions, $filter['options']);

                $filterForm->add($fieldName, $filter['type'], $filterOptions);
            }

            $form->add($filterForm);
        }

        if ($builder->hasSearch()) {
            $searchForm = $this->formFactory->createNamed($inputKeys->search, FormType::class, null, array('auto_initialize' => false));

            foreach ($builder->getSearchBuilder()->all() as $search) {
                $fieldName = str_replace('.', ':', $search['name']);
                $searchOptions = array(
                    'label' => ucfirst($fieldName),
                    'required' => false,
                );
                $searchOptions = array_merge($searchOptions, $search['options']);

                $searchForm->add($fieldName, $search['type'], $searchOptions);
            }

            $form->add($searchForm);
        }

        if ($builder->hasSort()) {
            $sortFormOptions = array_merge(array(
                'label' => 'Sort',
                'required' => false,
                'choices' => $builder->getSortBuilder()->getChoices(),
            ), $builder->getSortBuilder()->getFormOptions());
            $form->add($inputKeys->sort, ChoiceType::class, $sortFormOptions);
        }

        if ($builder->hasPageSize()) {
            $pageSizeFormOptions = array_merge(array(
                'label' => 'Page size',
                'required' => false,
                'placeholder' => false,
                'choices' => $builder->getPageSizeBuilder()->all(),
            ), $builder->getPageSizeBuilder()->getFormOptions());

            $form->add($inputKeys->pageSize, ChoiceType::class, $pageSizeFormOptions);
        }

        return $form;
    }
}
