<?php

namespace Nadia\Bundle\PaginatorBundle\Configuration;

interface QueryCompilerInterface
{
    /**
     * Compile a query target before fetching db data
     *
     * @param PaginatorBuilder $builder
     * @param mixed            $target  Target query instance to compile
     * @param mixed            $data    Data for query parameters
     *
     * @return void
     */
    public function compile(PaginatorBuilder $builder, $target, $data);

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
