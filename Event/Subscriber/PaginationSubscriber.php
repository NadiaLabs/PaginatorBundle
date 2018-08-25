<?php

namespace Nadia\Bundle\PaginatorBundle\Event\Subscriber;

use Nadia\Bundle\PaginatorBundle\Event\BeforeEvent;
use Nadia\Bundle\PaginatorBundle\Event\InputEvent;
use Nadia\Bundle\PaginatorBundle\Event\PaginationEvent;
use Nadia\Bundle\PaginatorBundle\Factory\PaginatorFormFactory;
use Nadia\Bundle\PaginatorBundle\Input\Input;
use Nadia\Bundle\PaginatorBundle\Input\InputFactory;
use Nadia\Bundle\PaginatorBundle\Pagination\Pagination;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class PaginationSubscriber
 */
class PaginationSubscriber implements EventSubscriberInterface
{
    /**
     * @var PaginatorFormFactory
     */
    private $formFactory;

    /**
     * @var InputFactory
     */
    private $inputFactory;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var bool
     */
    private $isLoaded = false;

    /**
     * @var Input
     */
    private $input;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * PaginatorFactory constructor.
     *
     * @param PaginatorFormFactory $formFactory
     * @param InputFactory         $inputFactory
     */
    public function __construct(PaginatorFormFactory $formFactory, InputFactory $inputFactory)
    {
        $this->formFactory = $formFactory;
        $this->inputFactory = $inputFactory;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $this->request = $event->getRequest();
    }

    /**
     * @param InputEvent $event
     */
    public function input(InputEvent $event)
    {
        $builder = $event->getBuilder();
        $options = $event->getOptions();

        $form = $this->formFactory->create($builder, $options);
        $input = $this->inputFactory->create($this->request, $form, $options);

        $event->form = $form;
        $event->input = $input;
    }

    /**
     * @param BeforeEvent $event
     */
    public function before(BeforeEvent $event)
    {
        if ($this->isLoaded) {
            return;
        }

        $dispatcher = $event->getEventDispatcher();

        $dispatcher->addSubscriber(new Doctrine\ORM\QueryBuilderSubscriber());

        $this->isLoaded = true;
    }

    /**
     * @param PaginationEvent $event
     */
    public function pagination(PaginationEvent $event)
    {
        $pagination = new Pagination();
        $routeParams = array_merge($this->request->query->all(), $this->request->attributes->get('_route_params', array()));

        $pagination->setCurrentRoute($this->request->attributes->get('_route'));
        $pagination->setCurrentRouteParams($routeParams);

        $event->setPagination($pagination);
        $event->stopPropagation();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'nadia_paginator.input' => array('input', 0),
            'nadia_paginator.before' => array('before', 0),
            'nadia_paginator.pagination' => array('pagination', 0),
        );
    }
}
