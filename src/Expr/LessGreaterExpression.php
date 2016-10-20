<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\RuleInterface;
use XTAIN\FilterQueryBuilder\UnsupportedOperatorException;

class LessGreaterExpression extends AbstractExpression implements ExpressionInterface
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
            case 'less':
                $expr = $this->builder->getQueryBuilder()->expr()->lt(
                    $this->rule->getField(),
                    ':' . $placeholder
                );
                break;
            case 'less_or_equal':
                $expr = $this->builder->getQueryBuilder()->expr()->lte(
                    $this->rule->getField(),
                    ':' . $placeholder
                );
                break;
            case 'greater':
                $expr = $this->builder->getQueryBuilder()->expr()->gt(
                    $this->rule->getField(),
                    ':' . $placeholder
                );
                break;
            case 'greater_or_equal':
                $expr = $this->builder->getQueryBuilder()->expr()->gte(
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
        return
            $rule->getOperator() == 'less' || $rule->getOperator() == 'less_or_equal' ||
            $rule->getOperator() == 'greater' || $rule->getOperator() == 'greater_or_equal';
    }
}