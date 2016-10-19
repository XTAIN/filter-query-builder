<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\RuleInterface;
use XTAIN\FilterQueryBuilder\UnsupportedOperatorException;

class NullExpression extends AbstractExpression implements ExpressionInterface
{
    /**
     * @return mixed
     */
    public function getQueryExpression()
    {
        switch ($this->rule->getOperator()) {
            case 'is_null':
                return $this->builder->getQueryBuilder()->expr()->isNull($this->rule->getField());
            case 'is_not_null':
                return $this->builder->getQueryBuilder()->expr()->isNotNull($this->rule->getField());
            default:
                throw new UnsupportedOperatorException('Operator not supported');
        }
    }

    /**
     * @param RuleInterface $rule
     *
     * @return bool
     */
    public static function supports(RuleInterface $rule)
    {
        return $rule->getOperator() == 'is_null' || $rule->getOperator() == 'is_not_null';
    }
}