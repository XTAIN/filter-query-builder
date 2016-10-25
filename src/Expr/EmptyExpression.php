<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\RuleInterface;
use XTAIN\FilterQueryBuilder\UnsupportedOperatorException;

class EmptyExpression extends AbstractExpression implements ExpressionInterface
{
    /**
     * @return mixed
     */
    public function getQueryExpression()
    {
        $placeholder = $this->builder->createPlaceholder(
            $this->rule->getField()
        );

        if ($this->rule->getOperator() == 'is_empty') {
            $expr = $this->builder->getQueryBuilder()->expr()->eq(
                $this->rule->getField(),
                ':' . $placeholder
            );
        } else {
            $expr = $this->builder->getQueryBuilder()->expr()->neq(
                $this->rule->getField(),
                ':' . $placeholder
            );
        }

        $this->builder->getQueryBuilder()->setParameter(
            $placeholder,
            '',
            \PDO::PARAM_STR
        );

        return $expr;
    }

    /**
     * @param RuleInterface $rule
     *
     * @return bool
     */
    public static function supports(RuleInterface $rule)
    {
        return $rule->getOperator() == 'is_empty' || $rule->getOperator() == 'is_not_empty';
    }
}