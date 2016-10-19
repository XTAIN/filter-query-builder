<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\RuleInterface;

abstract class AbstractExpression implements ExpressionInterface
{
    /**
     * @var BuilderInterface
     */
    protected $builder;

    /**
     * @var RuleInterface
     */
    protected $rule;

    /**
     * EqualHandler constructor.
     *
     * @param BuilderInterface $builder
     * @param RuleInterface $rule
     */
    public function __construct(
        BuilderInterface $builder,
        RuleInterface $rule
    ) {
        $this->builder = $builder;
        $this->rule = $rule;
    }
}