<?php

namespace NadiaProject\Bundle\PaginatorBundle\Builder;

use NadiaProject\Bundle\PaginatorBundle\Input\Input;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class PaginatorFormBuilder
 */
class PaginatorFormBuilder
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
     * @param PaginatorBuilder $builder
     * @param Input $input
     * @param array $typeOptions
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function build(PaginatorBuilder $builder, Input $input, array $typeOptions)
    {
        $data = [
            $typeOptions['paramNameSearch'] => $input->getSearch(),
            $typeOptions['paramNameFilter'] => $input->getFilters(),
            $typeOptions['paramNamePageSize'] => $input->getPageSize(),
        ];

        $form = $this->formFactory->createNamed($typeOptions['formName']);

        if ($builder->hasSearchForm()) {
            $searchParams = $builder->getSearchFormParameters();
            $form->add($typeOptions['paramNameSearch'], $searchParams['type'], $searchParams['options']);
        } else {
            $form->add($typeOptions['paramNameSearch'], TextType::class);
        }

        $filterForm = $this->formFactory->createNamed($typeOptions['paramNameFilter'], FormType::class, null, ['auto_initialize' => false]);

        foreach ($builder->getAllFilterFormParameters() as $parameters) {
            if (!$filterForm->has($parameters['alias'])) {
                $aliasForm = $this->formFactory->createNamed($parameters['alias'], FormType::class, null, ['auto_initialize' => false]);

                $filterForm->add($aliasForm);
            }

            $filterForm[$parameters['alias']]->add($parameters['name'], $parameters['type'], $parameters['options']);
        }

        $form->add($filterForm);
        $form->add($typeOptions['paramNamePageSize'], ChoiceType::class, ['choices' => $builder->getPageSizes()]);

        $form->setData($data);

        return $form->createView();
    }
}
