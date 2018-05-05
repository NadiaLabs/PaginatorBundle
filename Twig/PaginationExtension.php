<?php

namespace NadiaProject\Bundle\PaginatorBundle\Twig;

use NadiaProject\Bundle\PaginatorBundle\Pagination;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginationExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('nadia_pagination', [$this, 'render'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('nadia_pagination_filters', [$this, 'filters'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('nadia_pagination_sort', [$this, 'sort'], ['is_safe' => ['html'], 'needs_environment' => true]),
        );
    }

    /**
     * Renders the pagination template
     *
     * @param \Twig_Environment $env
     * @param Pagination $pagination
     * @param array $options {template: customViewTemplate, maxDisplayPages: 10}
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(\Twig_Environment $env, Pagination $pagination, array $options = [])
    {
        $options = array_merge([
            'maxDisplayPages' => 8,
        ], $options);
        $template = empty($options['template']) ? $pagination->getTemplate() : $options['template'];

        $current = $pagination->getCurrentPage();
        $max = $pagination->getMaxPageSize();
        $last = intval($current + $options['maxDisplayPages'] / 2);
        $last = $last < $options['maxDisplayPages'] ? $options['maxDisplayPages'] : $last;
        $last = $last > $max ? $max : $last;
        $first = $last - $options['maxDisplayPages'] + 1;
        $first = $first < 1 ? 1 : $first;

        $request = $pagination->getRequest();
        $paramNamePage = $pagination->getPaginatorOption('paramNamePage', 'page');
        $linkPrefix = $this->generateUrl($request, [$paramNamePage => '']);

        return $env->render(
            $template,
            [
                'pagination' => $pagination,
                'options' => $options,
                'first' => $first,
                'last' => $last,
                'current' => $current,
                'max' => $max,
                'linkPrefix' => $linkPrefix,
            ]
        );
    }

    /**
     * Renders the filters template
     *
     * @param \Twig_Environment $env
     * @param Pagination $pagination
     * @param array $options {form: formOptions, template: customViewTemplate}
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function filters(\Twig_Environment $env, Pagination $pagination, array $options = [])
    {
        $template = empty($options['template']) ? $pagination->getFiltersTemplate() : $options['template'];
        $formOptions = empty($options['formOptions']) ? [] : $options['formOptions'];
        $formOptions = array_merge(['method' => 'POST'], $formOptions);

        return $env->render(
            $template,
            [
                'pagination' => $pagination,
                'formOptions' => $formOptions,
            ]
        );
    }

    /**
     * Renders the pagination template
     *
     * @param \Twig_Environment $env
     * @param Pagination $pagination
     * @param string $sortBy
     * @param string $title
     * @param array $options {defaultSortDirection: 'ASC', template: customViewTemplate}
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sort(\Twig_Environment $env, Pagination $pagination, $sortBy, $title, array $options = [])
    {
        $options = array_merge([
            'defaultSortDirection' => 'ASC',
        ], $options);
        $template = empty($options['template']) ? $pagination->getSortTemplate() : $options['template'];
        $sortDirection = $options['defaultSortDirection'];
        $sortDirection = in_array($sortDirection, ['ASC', 'DESC']) ? $sortDirection : 'ASC';
        $currentSortDirection = '';

        $request = $pagination->getRequest();
        $paramNameSortBy = $pagination->getPaginatorOption('paramNameSortBy', 'sort_by');
        $paramNameSortDirection = $pagination->getPaginatorOption('paramNameSortDirection', 'sort_direction');

        if ($request->query->has($paramNameSortBy) && $request->query->has($paramNameSortDirection)) {
            $currentSortBy = $request->query->get($paramNameSortBy);

            if ($currentSortBy === $sortBy) {
                $currentSortDirection = $request->query->get($paramNameSortDirection);
                $sortDirection = $currentSortDirection === 'ASC' ? 'DESC' : 'ASC';
            }
        }

        $link = $this->generateUrl($request, [$paramNameSortBy => $sortBy, $paramNameSortDirection => $sortDirection]);

        return $env->render(
            $template,
            [
                'link' => $link,
                'title' => $title,
                'currentSortDirection' => $currentSortDirection,
            ]
        );
    }

    /**
     * Generate Url
     *
     * @param Request $request
     * @param array $replace
     *
     * @return string
     */
    private function generateUrl(Request $request, array $replace = [])
    {
        $linkPrefix = $request->getSchemeAndHttpHost().$request->getBaseUrl().$request->getPathInfo();
        $query = $request->query->all();

        foreach ($replace as $key => $value) {
            unset($query[$key]);

            $query[$key] = $value;
        }

        return $linkPrefix . '?' . (empty($query) ? '' : http_build_query($query));
    }
}
