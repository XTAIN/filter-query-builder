<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\RuleInterface;
use XTAIN\FilterQueryBuilder\UnsupportedOperatorException;

class EqualExpression extends AbstractExpression implements ExpressionInterface
{
    /**
     * @return mixed
     */
    public function getQueryExpression()
    {
        $placeholder = $this->builder->createPlaceholder(
            $this->rule->getField()
        );

        switch ($this->rule->getOperator()) {
            case 'equal':
                $expr = $this->builder->getQueryBuilder()->expr()->eq(
                    $this->rule->getField(),
                    ':' . $placeholder
                );
                break;
            case 'not_equal':
                $expr = $this->builder->getQueryBuilder()->expr()->neq(
                    $this->rule->getField(),
                    ':' . $placeholder
                );
                break;
            default:
                throw new UnsupportedOperatorException('Operator not supported');
        }

        $this->builder->setParameter($placeholder, $this->rule);

        return $expr;
    }

    /**
     * @param RuleInterface $rule
     *
     * @return bool
     */
    public static function supports(RuleInterface $rule)
    {
        return $rule->getOperator() == 'equal' || $rule->getOperator() == 'not_equal';
    }
}