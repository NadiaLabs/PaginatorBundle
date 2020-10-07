<?php

namespace Nadia\Bundle\PaginatorBundle\Event\Subscriber;

use Nadia\Bundle\PaginatorBundle\Event\BeforeEvent;
use Nadia\Bundle\PaginatorBundle\Event\PaginationEvent;
use Nadia\Bundle\PaginatorBundle\Factory\PaginatorFormFactory;
use Nadia\Bundle\PaginatorBundle\Input\InputFactory;
use Nadia\Bundle\PaginatorBundle\Pagination\Pagination;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
    private $paginatorFormFactory;

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
     * PaginatorFactory constructor.
     *
     * @param PaginatorFormFactory $paginatorFormFactory
     * @param InputFactory         $inputFactory
     */
    public function __construct(PaginatorFormFactory $paginatorFormFactory, InputFactory $inputFactory)
    {
        $this->paginatorFormFactory = $paginatorFormFactory;
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
     * @param BeforeEvent $event
     */
    public function before(BeforeEvent $event)
    {
        // Allow the compatibility for using in console or none-http-request situations
        if (!$this->request instanceof Request) {
            $this->request = new Request();
        }

        $this->addEventSubscribers($event->getEventDispatcher());

        $event->getBuilder()->build();

        $event->form = $this->paginatorFormFactory->create($event->getBuilder());
        $event->input = $this->inputFactory->create($this->request, $event->form, $event->getBuilder()->getTypeOptions());
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    private function addEventSubscribers(EventDispatcherInterface $dispatcher)
    {
        if ($this->isLoaded) {
            return;
        }

        $dispatcher->addSubscriber(new Doctrine\ORM\QueryBuilderSubscriber());

        $this->isLoaded = true;
    }

    /**
     * @param PaginationEvent $event
     */
    public function pagination(PaginationEvent $event)
    {
        $event->pagination = new Pagination($event->getBuilder());
        $routeParams = array_merge($this->request->query->all(), $this->request->attributes->get('_route_params', array()));

        $event->pagination->setCurrentRoute($this->request->attributes->get('_route'));
        $event->pagination->setCurrentRouteParams($routeParams);

        $event->stopPropagation();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'nadia_paginator.before' => array('before', 0),
            'nadia_paginator.pagination' => array('pagination', 0),
        );
    }
}
