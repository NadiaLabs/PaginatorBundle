<?php

namespace Nadia\Bundle\PaginatorBundle\Twig;

use Nadia\Bundle\PaginatorBundle\Configuration\SortInterface;
use Nadia\Bundle\PaginatorBundle\Pagination\Pagination;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Processor for Twig context (An array of parameters to pass to the Twig template)
 */
class ContextProcessor
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * ContextProcessor constructor.
     *
     * @param UrlGeneratorInterface $router
     * @param TranslatorInterface   $translator
     */
    public function __construct(UrlGeneratorInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * Get pages view variables
     *
     * @param Pagination $pagination A Pagination instance
     * @param array      $options    Format: {
     *     @var int    $range            The range of pages, default is 8
     *     @var string $firstPageText    First page display text
     *     @var string $lastPageText     Last page display text
     *     @var string $previousPageText Previous page display text
     *     @var string $nextPageText     Next page display text
     * }
     *
     * @return array
     */
    public function pages(Pagination $pagination, array $options = array())
    {
        $inputKeys = $pagination->getInputKeys();
        $input = $pagination->getInput();
        $route = $pagination->getCurrentRoute();
        $routeParams = $pagination->getCurrentRouteParams();
        $options = array_merge([
            'range' => $pagination->getOption('defaultPageRange'),
            'firstPageText' => 'First',
            'lastPageText' => 'Last',
            'previousPageText' => 'Prev',
            'nextPageText' => 'Next',
        ], $options);
        $count = $pagination->getCount();
        $current = $input->getPage();
        $maxPage = (int) ceil($count / $input->getLimit());
        $maxPage = $maxPage < 1 ? 1 : $maxPage;
        $range = $options['range'];
        $rangeLevel = intval($current / $range);
        $pages = array();

        $firstPage = [
            'number' => $range * $rangeLevel + 1,
            'url' => '#',
        ];
        $firstPage['text'] = empty($options['firstPageText']) ? $firstPage['number'] : $options['firstPageText'];
        if ($firstPage['number'] != $current) {
            $firstPage['url'] = $this->router->generate($route, array_merge($routeParams, [$inputKeys->page => 1]));
        }

        $lastPageNumber = $range * ($rangeLevel + 1);
        $lastPageNumber = $lastPageNumber > $maxPage ? $maxPage : $lastPageNumber;
        $lastPage = [
            'number' => $lastPageNumber,
            'url' => '#',
        ];
        $lastPage['text'] = empty($options['lastPageText']) ? $lastPage['number'] : $options['lastPageText'];
        if ($lastPage['number'] != $current) {
            $lastPage['url'] = $this->router->generate($route, array_merge($routeParams, [$inputKeys->page => $lastPageNumber]));
        }

        $previousPage = [
            'number' => $current - 1,
            'url' => '#',
        ];
        $previousPage['text'] = empty($options['previousPageText']) ? $previousPage['number'] : $options['previousPageText'];
        if ($previousPage['number'] >= 1 && $previousPage['number'] != $current) {
            $previousPage['url'] = $this->router->generate($route, array_merge($routeParams, [$inputKeys->page => $previousPage['number']]));
        }

        $nextPage = [
            'number' => $current + 1,
            'url' => '#',
        ];
        $nextPage['text'] = empty($options['nextPageText']) ? $nextPage['number'] : $options['nextPageText'];
        if ($nextPage['number'] <= $maxPage && $nextPage['number'] != $current) {
            $nextPage['url'] = $this->router->generate($route, array_merge($routeParams, [$inputKeys->page => $nextPage['number']]));
        }

        for ($i = $firstPage['number']; $i <= $lastPage['number']; ++$i) {
            $pages[] = [
                'number' => $i,
                'url' => $this->router->generate($route, array_merge($routeParams, [$inputKeys->page => $i])),
                'text' => $i,
            ];
        }

        return array(
            'current' => $current,
            'maxPage' => $maxPage,
            'firstPage' => $firstPage,
            'lastPage' => $lastPage,
            'previousPage' => $previousPage,
            'nextPage' => $nextPage,
            'pages' => $pages,
        );
    }

    /**
     * Get sort form view variables
     *
     * @param Pagination $pagination A Pagination instance
     * @param array      $options    Format: {
     *     @var array $attr Attributes for each filter's div container, ex: array('class' => 'foobar', ...)
     * }
     *
     * @return array
     */
    public function searches(Pagination $pagination, array $options = array())
    {
        return array(
            'filterForm' => $pagination->getFormView()->children[$pagination->getInputKeys()->search],
            'attributes' => (!empty($options['attr']) && is_array($options['attr'])) ? $options['attr'] : array(),
        );
    }

    /**
     * Get sort form view variables
     *
     * @param Pagination $pagination A Pagination instance
     * @param array      $options    Format: {
     *     @var array $attr Attributes for each filter's div container, ex: array('class' => 'foobar', ...)
     * }
     *
     * @return array
     */
    public function filters(Pagination $pagination, array $options = array())
    {
        return array(
            'filterForm' => $pagination->getFormView()->children[$pagination->getInputKeys()->filter],
            'attributes' => (!empty($options['attr']) && is_array($options['attr'])) ? $options['attr'] : array(),
        );
    }

    /**
     * Get sort form view variables
     *
     * @param Pagination $pagination A Pagination instance
     *
     * @return array
     */
    public function sortForm(Pagination $pagination)
    {
        return array(
            'sortForm' => $pagination->getFormView()->children[$pagination->getInputKeys()->sort],
        );
    }

    /**
     * Get sort link view variables
     *
     * @param Pagination $pagination A Pagination instance
     * @param string     $title      Sort title
     * @param string     $key        Sort key
     * @param string     $direction  Sort default direction (SortInterface::ASC or SortInterface::DESC)
     * @param array      $options    Format: {
     *     @var array $attr Html tag attributes, ex: array('class' => 'foobar', 'alt' => 'balabalabala...', ...)
     * }
     *
     * @return array
     */
    public function sortLink(Pagination $pagination, $title, $key, $direction = SortInterface::ASC, array $options = array())
    {
        $input = $pagination->getInput();
        $sortBuilder = $pagination->getBuilder()->getSortBuilder();
        $reversedDirection = $direction !== SortInterface::DESC ? SortInterface::DESC : SortInterface::ASC;
        $sort = $key . ' ' . $direction;
        $showDirection = false;

        if ($input->hasSort()) {
            $inputSort = $input->getSort();

            if ($inputSort['key'] === $key) {
                $sort = $sortBuilder->getReversed($key, $inputSort['direction']);
                $reversedDirection = $sort['direction'];
                $direction = $inputSort['direction'];
                $sort = $key . ' ' . $reversedDirection;
                $showDirection = true;
            }
        }

        $sessionEnabled = $pagination->getOption('sessionEnabled', false);
        $inputKeys = $pagination->getInputKeys();
        $routeParams = [$inputKeys->sort => $sort];

        if (!$sessionEnabled) {
            $routeParams = array_merge($pagination->getCurrentRouteParams(), $routeParams);
        }

        $url = $this->router->generate($pagination->getCurrentRoute(), $routeParams);
        $attributes = array('href' => $url);

        if (!empty($options['attr']) && is_array($options['attr'])) {
            $attributes = array_merge($attributes, $options['attr']);
        }

        return array(
            'title' => $title,
            'key' => $key,
            'direction' => $direction,
            'reversedDirection' => $reversedDirection,
            'showDirection' => $showDirection,
            'url' => $url,
            'attributes' => $attributes,
        );
    }

    /**
     * Get limit form view variables
     *
     * @param Pagination $pagination A Pagination instance
     *
     * @return array
     */
    public function limitForm(Pagination $pagination)
    {
        return array(
            'limitForm' => $pagination->getFormView()->children[$pagination->getInputKeys()->limit],
        );
    }
}
