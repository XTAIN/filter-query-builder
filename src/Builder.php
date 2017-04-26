<?php

namespace XTAIN\FilterQueryBuilder;

use Doctrine\ORM\QueryBuilder;
use XTAIN\FilterQueryBuilder\Expr\ConjunctionFactoryInterface;
use XTAIN\FilterQueryBuilder\Expr\ConjunctionInterface;
use XTAIN\FilterQueryBuilder\Expr\ExpressionFactoryInterface;
use XTAIN\FilterQueryBuilder\Expr\ExpressionInterface;

class Builder implements BuilderInterface
{
    /**
     * @var QueryBuilder
     */
    protected $builder;

    /**
     * @var ExpressionFactoryInterface[]
     */
    protected $expressionFactories;

    /**
     * @var ConjunctionFactoryInterface[]
     */
    protected $conjunctionFactories;

    /**
     * Builder constructor.
     *
     * @param QueryBuilder $builder
     * @param array        $expressionFactories
     * @param array        $conjunctionFactories
     */
    public function __construct(
        QueryBuilder $builder,
        array $expressionFactories,
        array $conjunctionFactories
    ) {
        $this->builder = $builder;
        $this->expressionFactories = $expressionFactories;
        $this->conjunctionFactories = $conjunctionFactories;
    }

    /**
     * @param QueryInterface $query
     *
     * @return void
     */
    public function apply(QueryInterface $query)
    {
        // This can happen if the querybuilder had no rules...
        if (is_array($query->getRules()) && count($query->getRules()) > 0) {
            $condition = $this->loopThroughRules(
                $query->getRules(),
                $query->getCondition()
            );

            $this->builder->andWhere(
                $condition->getQueryExpression()
            );
        }

        foreach ($query->getOrder() as $order) {
            $this->builder->addOrderBy(
                $order->getField(),
                $order->getDirection()
            );
        }
    }

    /**
     * @param RuleInterface[] $rules
     * @param string          $queryCondition
     *
     * @return ConjunctionInterface
     */
    protected function loopThroughRules(array $rules, $queryCondition = 'AND')
    {
        $condition = $this->createConjunction($queryCondition);

        foreach ($rules as $rule) {
            if ($this->isNested($rule)) {
                $condition->add(
                    $this->loopThroughRules(
                        $rule->getRules(),
                        $rule->getCondition()
                    )
                );
            } else {
                $condition->add(
                    $this->createExpression($rule)
                );
            }
        }

        return $condition;
    }

    /**
     * @param RuleInterface $rule
     *
     * @return bool
     */
    protected function isNested(RuleInterface $rule)
    {
        if (is_array($rule->getRules()) && count($rule->getRules()) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param string $queryCondition
     *
     * @return ConjunctionInterface
     * @throws UnsupportedConjunctionException
     */
    protected function createConjunction($queryCondition)
    {
        foreach ($this->conjunctionFactories as $conjunction) {
            try {
                return $conjunction->createSupportingConjunction($this, $queryCondition);
            } catch (UnsupportedConjunctionException $e) {
                continue;
            }
        }

        throw new UnsupportedConjunctionException(
            sprintf(
                'Cannot find conjunction supporting %s',
                $queryCondition
            )
        );
    }

    /**
     * @param RuleInterface $rule
     *
     * @return ExpressionInterface
     * @throws UnsupportedExpressionException
     */
    protected function createExpression(RuleInterface $rule)
    {
        foreach ($this->expressionFactories as $expression) {
            try {
                return $expression->createSupportingExpression($this, $rule);
            } catch (UnsupportedExpressionException $e) {
                continue;
            }
        }

        throw new UnsupportedExpressionException(
            sprintf(
                'Cannot find expression supporting %s',
                $rule->getOperator()
            )
        );
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->builder;
    }

    /**
     * @param string $prefix
     *
     * @return string
     */
    public function createPlaceholder($prefix)
    {
        return uniqid(preg_replace('/[^a-z]+/', '_', $prefix));
    }

    /**
     * @param string $placeholder
     * @param RuleInterface $rule
     */
    public function setParameter($placeholder, RuleInterface $rule)
    {
        switch ($rule->getType()) {
            case 'integer':
                $this->builder->setParameter($placeholder, (integer) $rule->getValue()->getValue(), \PDO::PARAM_INT);
                break;
            case 'double':
                $this->builder->setParameter($placeholder, (float) $rule->getValue()->getValue(), \PDO::PARAM_INT);
                break;
            case 'boolean':
                $this->builder->setParameter($placeholder, $rule->getValue()->getValue() == 'true' ? true : false, \PDO::PARAM_BOOL);
                break;
            default:
                $this->builder->setParameter($placeholder, $rule->getValue()->getValue(), \PDO::PARAM_STR);
                break;
        }
    }
}