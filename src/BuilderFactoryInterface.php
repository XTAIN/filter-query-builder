<?php

namespace XTAIN\FilterQueryBuilder;

use Doctrine\ORM\QueryBuilder;

interface BuilderFactoryInterface
{
    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return Builder
     */
    public function create(QueryBuilder $queryBuilder);
}