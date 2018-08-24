<?php

namespace Nadia\Bundle\PaginatorBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The Event class for preparing paginating processes
 */
class BeforeEvent extends Event
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * BeforeEvent constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}
