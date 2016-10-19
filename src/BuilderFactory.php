<?php

namespace XTAIN\FilterQueryBuilder;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class BuilderFactory
{
    /**
     * @var string[]
     */
    protected $expressions;

    /**
     * @var string[]
     */
    protected $conditions;

    /**
     * Builder constructor.
     *
     * @param array $expressions
     * @param array $conditions
     */
    public function __construct(
        array $expressions,
        array $conditions
    ) {
        $this->expressions = $expressions;
        $this->conditions = $conditions;
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return Builder
     */
    public function create(QueryBuilder $queryBuilder)
    {
        return new Builder(
            $queryBuilder,
            $this->expressions,
            $this->conditions
        );
    }
}