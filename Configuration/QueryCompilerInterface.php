<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

use Nadia\Bundle\PaginatorBundle\Input\Input;

interface QueryCompilerInterface
{
    /**
     * Compile a query target before fetching db data
     *
     * @param mixed            $target  Target query instance to compile
     * @param Input            $input   Data for query parameters
     * @param PaginatorBuilder $builder
     *
     * @return void
     */
    public function compile($target, Input $input, PaginatorBuilder $builder);

    /**
     * Return an array of callbacks for compiling the query target
     *
     * An example callback array:
     *
     * The array key is the field name
     * The array value is the method name to call (only the methods in this QueryCompiler class)
     *
     * array(
     *     'article.title' => 'buildArticleTitle',
     *     'article.author' => 'buildArticleAuthor',
     *     ....
     * )
     *
     * @return array
     */
    public function getCallbacks();
}
