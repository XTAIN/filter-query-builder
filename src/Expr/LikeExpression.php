<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\RuleInterface;
use XTAIN\FilterQueryBuilder\UnsupportedOperatorException;

class LikeExpression extends AbstractExpression implements ExpressionInterface
{
    /**
     * @return mixed
     */
    public function getQueryExpression()
    {
        $placeholder = $this->builder->createPlaceholder(
            $this->rule->getField()
        );

        $expr = $this->builder->getQueryBuilder()->expr()->like(
            $this->rule->getField(),
            ':' . $placeholder
        );

        if (substr($this->rule->getOperator(), 0, 4) == 'not_') {
            $expr = $this->builder->getQueryBuilder()->expr()->not($expr);
        }

        $pattern = $value = $this->rule->getValue()->getValue();

        if ($this->rule->getOperator() != 'like' && $this->rule->getOperator() != 'not_like') {
            $q = '';
            foreach (str_split($value) as $char) {
                $lower = strtolower($char);
                $upper = strtoupper($char);
                $q .= '['.$lower.$upper.']';
            }

            $value = $q;
            $pattern = $value;

            switch ($this->rule->getOperator()) {
                case 'contains':
                case 'not_contains':
                    $pattern = '%' . $value . '%';
                    break;
                case 'ends_with':
                case 'not_ends_with':
                    $pattern = '%' . $value;
                    break;
                case 'begins_with':
                case 'not_begins_with':
                    $pattern = $value . '%';
                    break;
            }
        }

        $this->builder->getQueryBuilder()->setParameter(
            $placeholder,
            $pattern,
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
        return
            $rule->getOperator() == 'like' || $rule->getOperator() == 'not_like' ||
            $rule->getOperator() == 'contains' || $rule->getOperator() == 'not_contains' ||
            $rule->getOperator() == 'ends_with' || $rule->getOperator() == 'not_ends_with' ||
            $rule->getOperator() == 'begins_with' || $rule->getOperator() == 'not_begins_with';
    }
}