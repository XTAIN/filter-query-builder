<?php

namespace XTAIN\FilterQueryBuilder\Expr;

class DefaultExpressionFactory extends AbstractExpressionFactory
{
    /**
     * @return string[]
     */
    protected function getClasses()
    {
        return array(
            EqualExpression::class,
            LessGreaterExpression::class,
            NullExpression::class,
            BetweenExpression::class
        );
    }
}