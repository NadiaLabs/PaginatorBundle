<?php

namespace Nadia\Bundle\PaginatorBundle\QueryBuilder;

use Doctrine\ORM\QueryBuilder;
use Nadia\Bundle\PaginatorBundle\Builder\PaginatorBuilder;

/**
 * Class ORMQueryBuilder
 */
class ORMQueryBuilder
{
    /**
     * @var PaginatorBuilder
     */
    protected $paginatorBuilder;

    /**
     * ListQueryBuilder constructor.
     *
     * ## $filterProcessors 產生 where 條件的 filter 處理器 (處理器使用 Closure 的型態來定義)
     *
     * 可以在這邊設定除了『=』等式之外的 where 條件
     *
     * 範例:
     *
     * 假設 Post 這個 entity 有個欄位 createdAt (欄位型態為 int(10) unsigned), 我們想要篩選一個時間範圍,
     * 希望可以將以下的 filters 參數帶入
     *
     * <code>
     * [
     *     'createdAtStart' => new DateTime('2017-04-01'),  // 代表 created_at 範圍的起始時間
     *     'createdAtEnd' => new DateTime('2017-04-30'),    // 代表 created_at 範圍的終止時間
     * ]
     * </code>
     *
     * 但是 `ListQueryBuilder` 預設只處理 `foo = 'bar'` 這樣的 where 條件, 沒有 created_at_start
     * 和 created_at_end 相對應的 filter 處理器, 所以我們要在 `filterProcessors` 這個屬性裡, 加上
     * 'created_at_start' 和 'created_at_end' 的 filter 處理器, 請參考以下的範例程式
     *
     * <code>
     * $filterProcessors = [];
     *
     * // 處理 createdAt 的起始範圍條件
     * $filterProcessors['createdAtStart'] = function(QueryBuilder $qb, $fieldName, $value) {
     *     $qb->andWhere('post.createdAt >= :createdAtStart');
     *     $qb->setParameter('createdAtStart', $value);
     * };
     *
     * // 處理 createdAt 的終止範圍條件
     * $filterProcessors['createdAtEnd'] = function(QueryBuilder $qb, $fieldName, $value) {
     *     $qb->andWhere('post.createdAt <= :createdAtEnd');
     *     $qb->setParameter('createdAtEnd', $value);
     * };
     *
     * $lqb = new ListQueryBuilder($filterProcessors);
     * $filters = [
     *     'createdAtStart' => new DateTime('2017-04-01'),
     *     'createdAtEnd' => new DateTime('2017-04-30'),
     * ];
     * $qb = $lqb->build($filters);
     * </code>
     *
     * ## $sortProcessors 產生排序條件的處理器 (處理器使用 Closure 的型態來定義)
     *
     * 可以在這邊設定除了既有欄位以外的排序條件 (像是複合欄位的排序條件)
     *
     * 範例:
     *
     * 假設 Post 這個 entity 想用 category 和 createdAt 做排序, 排序條件舉例如下, 我們使用
     * `_category_created_at` 作為自訂的排序欄位名稱
     *
     * <ul>
     * <li>category DESC, createdAt DESC (定義為 [_category_created_at => DESC] 的排序設定)</li>
     * <li>category ASC, createdAt DESC  (定義為 [_category_created_at => ASC] 的排序設定)</li>
     * </ul>
     *
     * 但是 `ListQueryBuilder` 沒有 `_category_created_at` 的排序處理器, 所以我們要在
     * `sortProcessors` 這個屬性裡, 加上相對應的排序處理器, 請參考以下的範例程式
     *
     * <code>
     * $sortProcessors = [];
     *
     * // 處理 createdAt 的起始範圍條件
     * $sortProcessors['_category_created_at'] = function(QueryBuilder $qb, $fieldName, $direction) {
     *     $qb->addOrderBy('category', $direction);
     *     $qb->addOrderBy('createdAt', 'DESC');
     * };
     *
     * $lqb = new ListQueryBuilder([], sortProcessors);
     * $sorts = ['_category_created_at' => 'ASC'];
     * $qb = $lqb->build([], $sorts);
     * </code>
     *
     * @param PaginatorBuilder $paginatorBuilder
     */
    public function __construct(PaginatorBuilder $paginatorBuilder)
    {
        $this->paginatorBuilder = $paginatorBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function build(QueryBuilder $qb, $search = '', array $filters = [], array $sorts = [], $limit = 0, $offset = 0)
    {
        $searchProcessor = $this->paginatorBuilder->getSearchProcessor();
        $filterProcessors = $this->paginatorBuilder->getFilterProcessors();
        $sortProcessors = $this->paginatorBuilder->getSortProcessors();
        $searchColumns = $this->paginatorBuilder->getSearchColumns();
        $parameterCount = 0;

        foreach ($filters as $alias => $filter) {
            foreach ($filter as $key => $value) {
                if ('' === $value) {
                    continue;
                }

                $fieldName = $alias . '.' . $key;

                if (!empty($filterProcessors[$fieldName]) && is_callable($filterProcessors[$fieldName])) {
                    call_user_func($filterProcessors[$fieldName], $qb, $fieldName, $value);
                } else {
                    if (is_array($value)) {
                        $bindParameters = [];

                        foreach ($value as $v) {
                            $bindParameters[] = '?' . $parameterCount;

                            $qb->setParameter($parameterCount++, $v);
                        }

                        $qb->andWhere($qb->expr()->in($fieldName, $bindParameters));
                    } else {
                        $qb->andWhere($qb->expr()->eq($fieldName, '?' . $parameterCount));
                        $qb->setParameter($parameterCount++, $value);
                    }
                }
            }
        }

        if (!empty($search) && !empty($searchColumns)) {
            call_user_func($searchProcessor, $qb, $searchColumns, $search);
        }

        if (!empty($sorts)) {
            $directionList = ['ASC' => 'ASC', 'DESC' => 'DESC'];

            foreach ($sorts as $fieldName => $direction) {
                $direction = strtoupper($direction);
                $direction = !isset($directionList[$direction]) ? 'ASC' : $direction;

                if (!empty($sortProcessors[$fieldName])) {
                    call_user_func($sortProcessors[$fieldName], $qb, $fieldName, $direction);
                } else {
                    $qb->addOrderBy($fieldName, $direction);
                }
            }
        }

        if (!empty($limit)) {
            $qb->setMaxResults($limit);
        }

        if (!empty($offset)) {
            $qb->setFirstResult($offset);
        }

        return $qb;
    }
}
