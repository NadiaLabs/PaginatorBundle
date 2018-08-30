<?php

namespace AppBundle\Paginator\Movies;

use Nadia\Bundle\PaginatorBundle\Configuration\AbstractPaginatorType;
use Nadia\Bundle\PaginatorBundle\Configuration\FilterBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\PageSizeBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\SearchBuilder;
use Nadia\Bundle\PaginatorBundle\Configuration\Sort;
use Nadia\Bundle\PaginatorBundle\Configuration\SortBuilder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaginatorType extends AbstractPaginatorType
{
    /**
     * {@inheritdoc}
     */
    public function buildSearch(SearchBuilder $builder, array &$options)
    {
        $formOptions = [
            'label' => 'Search',
            'attr' => ['placeholder' => 'Enter keywords...'],
        ];
        $builder->add('movie.any', ['movie.title', 'movie.description', 'director.name'], TextType::class, $formOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function buildFilter(FilterBuilder $builder, array &$options)
    {
        $movieCompanies = array_combine($options['movieCompanies'], $options['movieCompanies']);

        $builder->add('movie.releasedAtStart', DateType::class, ['widget' => 'single_text', 'attr' => ['placeholder' => 'Enter a date...']]);
        $builder->add('movie.releasedAtEnd', DateType::class, ['widget' => 'single_text', 'attr' => ['placeholder' => 'Enter a date...']]);
        $builder->add('movie.company', ChoiceType::class, ['choices' => $movieCompanies, 'placeholder' => '-- Select a company --']);

        $builder->setQueryCompiler(new FilterQueryCompiler());
    }

    /**
     * {@inheritdoc}
     */
    public function buildSort(SortBuilder $builder, array &$options)
    {
        $builder->add('movie.title', Sort::ASC, 'movie.title ASC', 'Movie Title Ascending');
        $builder->add('movie.title', Sort::DESC, 'movie.title DESC', 'Movie Title Descending');
        $builder->add('movie.description', Sort::ASC, 'movie.description ASC', 'Movie Description Ascending');
        $builder->add('movie.description', Sort::DESC, 'movie.description DESC', 'Movie Description Descending');
        $builder->add('movie.releasedAt', Sort::ASC, 'movie.releasedAt ASC',  'Movie ReleasedAt Ascending');
        $builder->add('movie.releasedAt', Sort::DESC, 'movie.releasedAt DESC', 'Movie ReleasedAt Descending');

        $builder->setFormOptions([
            'placeholder' => '-- Select sorting --',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildPageSize(PageSizeBuilder $builder, array &$options)
    {
        $pageSizes = [5 => 5, 10 => 10, 20 => 20, 50 => 50, 100 => 100, 150 => 150, 200 => 200];

        $builder->add($pageSizes);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormOptions(array &$options)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('movieCompanies', []);
    }
}
