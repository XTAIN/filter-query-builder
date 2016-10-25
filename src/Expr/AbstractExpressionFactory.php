<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\RuleInterface;
use XTAIN\FilterQueryBuilder\UnsupportedExpressionException;

/**
 * Class AbstractExpressionFactory
 * @package XTAIN\FilterQueryBuilder\Expr
 */
abstract class AbstractExpressionFactory implements ExpressionFactoryInterface
{
    /**
     * @return string[]
     */
    abstract protected function getClasses();

    /**
     * @param BuilderInterface $builder
     * @param RuleInterface    $rule
     *
     * @return ExpressionInterface[]
     */
    public function createExpressions(
        BuilderInterface $builder,
        RuleInterface $rule
    ) {
        $conjunctions = array();

        foreach ($this->getClasses() as $class) {
            $conjunctions[] = new $class($builder, $rule);
        }

        return $conjunctions;
    }

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
    ) {
        foreach ($this->getClasses() as $class) {
            if (call_user_func(array($class, 'supports'), $rule)) {
                return new $class($builder, $rule);
            }
        }

        throw new UnsupportedExpressionException(
            'Cannot find expression supporting rule'
        );
    }
}