<?php

namespace XTAIN\FilterQueryBuilder;

use Doctrine\ORM\QueryBuilder;
use XTAIN\FilterQueryBuilder\Expr\ConjunctionInterface;
use XTAIN\FilterQueryBuilder\Expr\ExpressionInterface;

class Builder implements BuilderInterface
{
    /**
     * @var QueryBuilder
     */
    protected $builder;

    /**
     * @var string[]
     */
    protected $expressions;

    /**
     * @var string[]
     */
    protected $conditions;

    /**
     * Builder constructor.
     *
     * @param QueryBuilder $builder
     * @param array        $expressions
     * @param array        $conditions
     */
    public function __construct(
        QueryBuilder $builder,
        array $expressions,
        array $conditions
    ) {
        $this->expressions = $expressions;
        $this->conditions = $conditions;
    }

    /**
     * @param Rule $rule
     *
     * @return void
     */
    public function apply(Rule $rule)
    {
        $query = json_decode('{
          "condition": "AND",
          "rules": [
            {
              "id": "price",
              "field": "price",
              "type": "double",
              "input": "text",
              "operator": "less",
              "value": "10.25"
            },
            {
              "condition": "OR",
              "rules": [
                {
                  "id": "category",
                  "field": "category",
                  "type": "integer",
                  "input": "select",
                  "operator": "equal",
                  "value": "2"
                },
                {
                  "id": "category",
                  "field": "category",
                  "type": "integer",
                  "input": "select",
                  "operator": "equal",
                  "value": "1"
                }
              ]
            }
          ]
        }');

        // This can happen if the querybuilder had no rules...
        if (!$this->isNested($rule)) {
            return null;
        }

        $condition = $this->loopThroughRules(
            $rule->getRules(),
            $rule->getCondition()
        );

        $this->builder->andWhere(
            $condition->getQueryExpression()
        );
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
     * @throws UnsupportedExpressionException
     */
    protected function createConjunction($queryCondition)
    {
        foreach ($this->conditions as $condition) {
            if (call_user_func(array($condition, 'supports'), $queryCondition)) {
                return new $condition($this);
            }
        }

        throw new UnsupportedExpressionException(
            sprintf(
                'Cannot find condition containing %s',
                $queryCondition
            )
        );
    }

    /*
     * @param QueryBuilder $builder
     * @param string $placeholder
     * @param RuleInterface $rule
     */
    /**
     * @param RuleInterface $rule
     *
     * @return ExpressionInterface
     */
    protected function createExpression(RuleInterface $rule)
    {
        foreach ($this->expressions as $expression) {
            if (call_user_func(array($expression, 'supports'), $rule)) {
                return new $expression($this, $rule);
            }
        }

        throw new UnsupportedExpressionException(
            sprintf(
                'Cannot find expression for rule %s',
                $rule
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
        return uniqid($prefix);
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