<?php

namespace XTAIN\FilterQueryBuilder;

use Doctrine\ORM\QueryBuilder;
use XTAIN\FilterQueryBuilder\Expr\ConjunctionFactoryInterface;
use XTAIN\FilterQueryBuilder\Expr\ExpressionFactoryInterface;

class BuilderFactory implements BuilderFactoryInterface
{
    /**
     * @var ExpressionFactoryInterface[]
     */
    protected $expressions;

    /**
     * @var ConjunctionFactoryInterface[]
     */
    protected $conjunctions;

    /**
     * Builder constructor.
     *
     * @param array $expressions
     * @param array $conjunctions
     */
    public function __construct(
        array $expressions,
        array $conjunctions
    ) {
        $this->expressions = $expressions;
        $this->conjunctions = $conjunctions;
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
            $this->conjunctions
        );
    }
}