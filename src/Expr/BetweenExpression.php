<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\RuleInterface;

class BetweenExpression extends AbstractExpression implements ExpressionInterface
{
    /**
     * @return mixed
     */
    public function getQueryExpression()
    {
        $placeholderA = $this->builder->createPlaceholder(
            $this->rule->getField() . '_a'
        );

        $placeholderB = $this->builder->createPlaceholder(
            $this->rule->getField() . '_b'
        );

        $values = $this->rule->getValue()->getValue();

        $expr = $this->builder->getQueryBuilder()->expr()->between(
            $this->rule->getField(),
            $placeholderA,
            $placeholderB
        );

        if ($this->rule->getOperator() == 'not_between') {
            $expr = $this->builder->getQueryBuilder()->expr()->not(
                $expr
            );
        }

        foreach ($values as $i => $value) {
            if (ctype_digit($value)) {
                $type = \PDO::PARAM_INT;
                $value = (int) $value;
            } else {
                $type = \PDO::PARAM_STR;
                $value = (float) $value;
            }

            $this->builder->getQueryBuilder()->setParameter(
                $i == 0 ? $placeholderA : $placeholderB,
                $value,
                $type
            );
        }

        return $expr;
    }

    /**
     * @param RuleInterface $rule
     *
     * @return bool
     */
    public static function supports(RuleInterface $rule)
    {
        return $rule->getOperator() == 'between' || $rule->getOperator() == 'not_between';
    }
}