<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Paginator\Movies\PaginatorType;
use Doctrine\Common\Collections\Collection;
use Nadia\Bundle\PaginatorBundle\Pagination\Pagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends Controller
{
    /**
     * @Route("/movies", name="movies")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $options = [
            'movieCompanies' => [
                'Warner Bros.',
                'Sony Pictures',
                'Walt Disney',
                'Universal Pictures',
                '20th Century Fox',
                'Paramount Pictures',
                'Lionsgate Films',
                'The Weinstein Company',
                'DreamWorks Pictures',
            ],
        ];
        $paginator = $this->get('nadia_paginator.paginator_factory')->create(PaginatorType::class, $options);

        $qb = $this->getDoctrine()->getRepository(Movie::class)
            ->createQueryBuilder('movie')
            ->select(['movie', 'director'])
            ->leftJoin('movie.director', 'director')
        ;
        /** @var Movie[]|Collection|Pagination $pagination */
        $pagination = $paginator->paginate($qb);

        return $this->render('@App/Movie/index.html.twig', ['pagination' => $pagination]);
    }
}
