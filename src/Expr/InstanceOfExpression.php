<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\RuleInterface;
use XTAIN\FilterQueryBuilder\UnsupportedOperatorException;

class InstanceOfExpression extends AbstractExpression implements ExpressionInterface
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
            case 'instance_of':
                $expr = $this->builder->getQueryBuilder()->expr()->isInstanceOf(
                    $this->rule->getField(),
                    ':' . $placeholder
                );
                break;
            case 'not_instance_of':
                $expr =  $this->builder->getQueryBuilder()->expr()->not(
                    $this->builder->getQueryBuilder()->expr()->isInstanceOf(
                        $this->rule->getField(),
                        ':' . $placeholder
                    )
                );
                break;
            default:
                throw new UnsupportedOperatorException('Operator not supported');
        }

        $this->builder->getQueryBuilder()->setParameter(
            $placeholder,
            $this->builder->getQueryBuilder()->getEntityManager()->getClassMetadata($this->rule->getValue()->getValue()),
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
        return $rule->getOperator() == 'instance_of' || $rule->getOperator() == 'not_instance_of';
    }
}