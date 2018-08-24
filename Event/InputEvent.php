<?php

namespace Nadia\Bundle\PaginatorBundle\Event;

use Nadia\Bundle\PaginatorBundle\Configuration\PaginatorBuilder;
use Nadia\Bundle\PaginatorBundle\Input\Input;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;

/**
 * The Event class when build form & input instances
 */
class InputEvent extends Event
{
    /**
     * @var FormInterface
     */
    public $form;

    /**
     * @var Input
     */
    public $input;

    /**
     * @var PaginatorBuilder
     */
    private $builder;

    /**
     * @var array
     */
    private $options;

    /**
     * InputEvent constructor.
     *
     * @param PaginatorBuilder $builder
     * @param array            $options
     */
    public function __construct(PaginatorBuilder $builder, array $options)
    {
        $this->builder = $builder;
        $this->options = $options;
    }

    /**
     * @return PaginatorBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
