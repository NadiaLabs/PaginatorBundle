<?php

namespace Nadia\Bundle\PaginatorBundle\Twig;

use Nadia\Bundle\PaginatorBundle\Configuration\Sort;
use Nadia\Bundle\PaginatorBundle\Pagination\Pagination;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * ContextProcessor constructor.
     *
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
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
        $maxPage = (int) ceil($count / $input->getPageSize());
        $maxPage = $maxPage < 1 ? 1 : $maxPage;
        $range = $options['range'];
        $rangeLevel = intval($current / $range);
        $pages = array();

        if (array_key_exists($inputKeys->getReset(), $routeParams)) {
            unset($routeParams[$inputKeys->getReset()]);
            unset($routeParams[$inputKeys->getSearch()]);
            unset($routeParams[$inputKeys->getFilter()]);
            unset($routeParams[$inputKeys->getSort()]);
            unset($routeParams[$inputKeys->getPage()]);
        }

        $firstPage = [
            'number' => $range * $rangeLevel + 1,
            'url' => '#',
        ];
        $firstPage['text'] = empty($options['firstPageText']) ? $firstPage['number'] : $options['firstPageText'];
        if ($firstPage['number'] != $current) {
            $firstPage['url'] = $this->router->generate($route, array_merge($routeParams, [$inputKeys->getPage() => 1]));
        }

        $lastPageNumber = $range * ($rangeLevel + 1);
        $lastPageNumber = $lastPageNumber > $maxPage ? $maxPage : $lastPageNumber;
        $lastPage = [
            'number' => $lastPageNumber,
            'url' => '#',
        ];
        $lastPage['text'] = empty($options['lastPageText']) ? $lastPage['number'] : $options['lastPageText'];
        if ($lastPage['number'] != $current) {
            $lastPage['url'] = $this->router->generate($route, array_merge($routeParams, [$inputKeys->getPage() => $lastPageNumber]));
        }

        $previousPage = [
            'number' => $current - 1,
            'url' => '#',
        ];
        $previousPage['text'] = empty($options['previousPageText']) ? $previousPage['number'] : $options['previousPageText'];
        if ($previousPage['number'] >= 1 && $previousPage['number'] != $current) {
            $previousPage['url'] = $this->router->generate($route, array_merge($routeParams, [$inputKeys->getPage() => $previousPage['number']]));
        }

        $nextPage = [
            'number' => $current + 1,
            'url' => '#',
        ];
        $nextPage['text'] = empty($options['nextPageText']) ? $nextPage['number'] : $options['nextPageText'];
        if ($nextPage['number'] <= $maxPage && $nextPage['number'] != $current) {
            $nextPage['url'] = $this->router->generate($route, array_merge($routeParams, [$inputKeys->getPage() => $nextPage['number']]));
        }

        for ($i = $firstPage['number']; $i <= $lastPage['number']; ++$i) {
            $pages[] = [
                'number' => $i,
                'url' => $this->router->generate($route, array_merge($routeParams, [$inputKeys->getPage() => $i])),
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
            'options' => $options,
            'paginatorTranslationDomain' => $pagination->getOption('paginatorTranslationDomain'),
        );
    }

    /**
     * Get sort form view variables
     *
     * @param Pagination $pagination A Pagination instance
     * @param array      $options
     *
     * @return array
     */
    public function searches(Pagination $pagination, array $options = array())
    {
        return array(
            'searchForm' => $pagination->getSearchForm(),
            'options' => $options,
        );
    }

    /**
     * Get sort form view variables
     *
     * @param Pagination $pagination A Pagination instance
     * @param array      $options    Format: {
     *     @var string[] $excludes Excluded column names, hide those column names
     *     @var string[] $includes Included column names, show those column names
     * }
     *
     * @return array
     */
    public function filters(Pagination $pagination, array $options = array())
    {
        $form = $pagination->getFilterForm();
        $includes = empty($options['includes']) ? [] : (array) $options['includes'];
        $excludes = empty($options['excludes']) ? [] : (array) $options['excludes'];

        $includes = array_map([$this, 'replaceDot2colon'], $includes);
        $includes = empty($includes) ? array_keys($form->children) : $includes;
        $excludes = array_map([$this, 'replaceDot2colon'], $excludes);

        return array(
            'filterForm' => $form,
            'options' => $options,
            'validColumns' => array_diff($includes, $excludes),
        );
    }

    /**
     * Replace . (dot) to : (colon)
     *
     * @param string $string
     *
     * @return array
     */
    private function replaceDot2colon($string)
    {
        return str_replace('.', ':', $string);
    }

    /**
     * Get sort selection view variables
     *
     * @param Pagination $pagination A Pagination instance
     * @param array      $options
     *
     * @return array
     */
    public function sorts(Pagination $pagination, $options = array())
    {
        return array(
            'sortForm' => $pagination->getFormView()->children[$pagination->getInputKeys()->getSort()],
            'options' => $options,
        );
    }

    /**
     * Get sort link view variables
     *
     * @param Pagination $pagination A Pagination instance
     * @param string     $title      Sort title
     * @param string     $key        Sort key
     * @param string     $direction  Sort default direction (SortInterface::ASC or SortInterface::DESC)
     * @param array      $options
     *
     * @return array
     */
    public function sortLink(Pagination $pagination, $title, $key, $direction = Sort::ASC, array $options = array())
    {
        $input = $pagination->getInput();
        $sortBuilder = $pagination->getBuilder()->getSortBuilder();
        $reversedDirection = $direction !== Sort::DESC ? Sort::DESC : Sort::ASC;
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
        $routeParams = [$inputKeys->getSort() => $sort];

        if (!$sessionEnabled) {
            $routeParams = array_merge($pagination->getCurrentRouteParams(), $routeParams);
        }
        if (array_key_exists($inputKeys->getReset(), $routeParams)) {
            unset($routeParams[$inputKeys->getReset()]);
            unset($routeParams[$inputKeys->getSearch()]);
            unset($routeParams[$inputKeys->getFilter()]);
            unset($routeParams[$inputKeys->getPage()]);
        }
        if (array_key_exists($inputKeys->getPage(), $routeParams)) {
            unset($routeParams[$inputKeys->getPage()]);
        }

        $url = $this->router->generate($pagination->getCurrentRoute(), $routeParams);

        return array(
            'title' => $title,
            'key' => $key,
            'direction' => $direction,
            'reversedDirection' => $reversedDirection,
            'showDirection' => $showDirection,
            'url' => $url,
            'options' => $options,
        );
    }

    /**
     * Get page sizes view variables
     *
     * @param Pagination $pagination A Pagination instance
     * @param array      $options
     *
     * @return array
     */
    public function pageSizes(Pagination $pagination, $options = array())
    {
        return array(
            'pageSizeForm' => $pagination->getFormView()->children[$pagination->getInputKeys()->getPageSize()],
            'options' => $options,
        );
    }
}
