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
     * @var FilterCollector
     */
    protected $filterCollector;

    /**
     * Builder constructor.
     *
     * @param array           $expressions
     * @param array           $conjunctions
     * @param FilterCollector $filterCollector
     */
    public function __construct(
        array $expressions,
        array $conjunctions,
        FilterCollector $filterCollector
    ) {
        $this->expressions = $expressions;
        $this->conjunctions = $conjunctions;
        $this->filterCollector = $filterCollector;
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
            $this->conjunctions,
            $this->filterCollector->getConfiguration()
        );
    }
}