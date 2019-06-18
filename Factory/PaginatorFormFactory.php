<?php

namespace Nadia\Bundle\PaginatorBundle\Factory;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\InputKeys;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * PaginatorFactory constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param TranslatorInterface  $translator
     */
    public function __construct(FormFactoryInterface $formFactory, TranslatorInterface $translator)
    {
        $this->formFactory = $formFactory;
        $this->translator =$translator;
    }

    /**
     * Create a FormInterface instance for submitting and rendering
     *
     * @param PaginatorBuilder $builder
     *
     * @return FormInterface
     */
    public function create(PaginatorBuilder $builder)
    {
        $options = $builder->getTypeOptions();
        $formOptions = array(
            'csrf_protection' => false,
            'method' => $options['sessionEnabled'] ? 'POST' : 'GET',
            'attr' => array(
                'id' => 'pagination-'.time(),
            ),
        );
        $formOptions = array_merge($formOptions, $builder->getFormOptions());
        $form = $this->formFactory->createNamed(null, 'Symfony\Component\Form\Extension\Core\Type\FormType', null, $formOptions);

        /** @var InputKeys $inputKeys */
        $inputKeys = $options['inputKeys'];

        if ($builder->hasFilter()) {
            $filterForm = $this->formFactory->createNamed($inputKeys->getFilter(), 'Symfony\Component\Form\Extension\Core\Type\FormType', null, array('auto_initialize' => false));

            foreach ($builder->getFilterBuilder()->all() as $filter) {
                $fieldName = str_replace('.', ':', $filter['name']);
                $filterOptions = array(
                    'label' => ucfirst($fieldName),
                    'required' => false,
                    'translation_domain' => $options['translationDomain'],
                );
                $filterOptions = array_merge($filterOptions, $filter['options']);

                $filterForm->add($fieldName, $filter['type'], $filterOptions);
            }

            $form->add($filterForm);
        }

        if ($builder->hasSearch()) {
            $searchForm = $this->formFactory->createNamed($inputKeys->getSearch(), 'Symfony\Component\Form\Extension\Core\Type\FormType', null, array('auto_initialize' => false));

            foreach ($builder->getSearchBuilder()->all() as $search) {
                $fieldName = str_replace('.', ':', $search['name']);
                $searchOptions = array(
                    'label' => ucfirst($fieldName),
                    'required' => false,
                    'translation_domain' => $options['translationDomain'],
                );
                $searchOptions = array_merge($searchOptions, $search['options']);

                $searchForm->add($fieldName, $search['type'], $searchOptions);
            }

            $form->add($searchForm);
        }

        if ($builder->hasSort()) {
            $sortFormOptions = array_merge(array(
                'label' => $this->translator->trans('Sort', [], $options['paginatorTranslationDomain']),
                'required' => false,
                'choices' => $builder->getSortBuilder()->getChoices(),
                'translation_domain' => $options['translationDomain'],
            ), $builder->getSortBuilder()->getFormOptions());
            $form->add($inputKeys->getSort(), 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', $sortFormOptions);
        }

        if ($builder->hasPageSize()) {
            $pageSizeFormOptions = array_merge(array(
                'label' => $this->translator->trans('Page size', [], $options['paginatorTranslationDomain']),
                'required' => false,
                'placeholder' => false,
                'choices' => $builder->getPageSizeBuilder()->all(),
                'translation_domain' => $options['translationDomain'],
            ), $builder->getPageSizeBuilder()->getFormOptions());

            $form->add($inputKeys->getPageSize(), 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', $pageSizeFormOptions);
        }

        return $form;
    }
}
