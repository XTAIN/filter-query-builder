<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\RuleInterface;
use XTAIN\FilterQueryBuilder\UnsupportedExpressionException;

/**
 * Class ExpressionFactoryInterface
 * @package XTAIN\FilterQueryBuilder\Expr
 */
interface ExpressionFactoryInterface
{
    /**
     * @param BuilderInterface $builder
     * @param RuleInterface    $rule
     *
     * @return ExpressionInterface[]
     */
    public function createExpressions(
        BuilderInterface $builder,
        RuleInterface $rule
    );

    /**
     * @param BuilderInterface $builder
     * @param RuleInterface    $rule
     *
     * @return ExpressionInterface
     * @throws UnsupportedExpressionException
     */
    public function createSupportingExpression(
        BuilderInterface $builder,
        RuleInterface $rule
    );
}