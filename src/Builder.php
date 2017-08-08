<?php

namespace XTAIN\FilterQueryBuilder;

use Doctrine\ORM\QueryBuilder;
use XTAIN\FilterQueryBuilder\Configuration\Filter;
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
     * @var Configuration
     */
    protected $configuration;

    /**
     * Builder constructor.
     *
     * @param QueryBuilder  $builder
     * @param array         $expressionFactories
     * @param array         $conjunctionFactories
     * @param Configuration $configuration
     */
    public function __construct(
        QueryBuilder $builder,
        array $expressionFactories,
        array $conjunctionFactories,
        Configuration $configuration = null
    ) {
        $this->builder = $builder;
        $this->expressionFactories = $expressionFactories;
        $this->conjunctionFactories = $conjunctionFactories;
        $this->configuration = $configuration;
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

        $this->applyOrderBy($query);
    }

    /**
     * @param QueryInterface $query
     *
     * @return void
     */
    protected function applyOrderBy(QueryInterface $query)
    {
        foreach ($query->getOrder() as $order) {
            $field = $order->getField();
            $direction = $order->getDirection();

            $configuration = $this->configuration->getFilterById($field);

            if ($configuration !== null) {
                $values = $configuration->getOrderValues();

                if ($values !== null) {
                    if (is_callable($values)) {
                        $values = call_user_func($values, $configuration, $this->builder, $query);
                    }
                }

                switch ($configuration->getOrder()) {
                    case Filter::ORDER_NONE:
                        continue;
                    case Filter::ORDER_DEFAULT:
                        break;
                    case Filter::ORDER_MAP_ALPHANUM:
                        asort($values);
                    case Filter::ORDER_MAP_FIXED:
                        $field = $this->buildOrderByCase($field, $configuration, $values);
                        break;
                }
            }

            $this->builder->addOrderBy(
                $field,
                $direction
            );
        }
    }

    /**
     * @param string               $field
     * @param Configuration\Filter $filter
     * @param string[]             $values
     *
     * @return string
     */
    protected function buildOrderByCase($field, Configuration\Filter $filter, array $values)
    {
        $placeholder = $this->createPlaceholder('__ord_'.$field);

        $case = '';
        $highest = 0;

        foreach ($values as $match => $value) {
            $compare = 'LIKE';
            if ($filter->getType() == 'integer' || $filter->getType() == 'double' || $filter->getType() == 'boolean') {
                $compare = '=';
            }
            $case .= '        WHEN '.$field." ".$compare." ".$this->quoteByType($match, $filter->getType())." THEN ".$highest."\n";
            $highest++;
        }

        $dql = sprintf(
            '('."\n".
            '    CASE'."\n".
            '%s'.
            '        ELSE %s'."\n".
            '    END'."\n".
            ') AS HIDDEN %s',
            $case,
            $highest,
            $placeholder
        );

        $this->getQueryBuilder()->addSelect($dql);

        return $placeholder;
    }

    /**
     * @param string $value
     * @param string $type
     *
     * @return string
     */
    protected function quoteByType($value, $type)
    {
        switch ($type) {
            case 'integer':
                return (integer) $value;
                break;
            case 'double':
                return (float) $value;
                break;
            case 'boolean':
                return $value ? 1 : 0;
                break;
            default:
                return $this->getQueryBuilder()->getEntityManager()->getConnection()->getDatabasePlatform()->quoteStringLiteral($value);
                break;
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function quoteIdentifier($value)
    {
        return $this->getQueryBuilder()->getEntityManager()->getConnection()->getDatabasePlatform()->quoteIdentifier($value);
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
     * @param mixed  $value
     * @param string $type
     */
    public function setParameterByType($placeholder, $value, $type)
    {
        switch ($type) {
            case 'integer':
                $this->builder->setParameter($placeholder, (integer) $value, \PDO::PARAM_INT);
                break;
            case 'double':
                $this->builder->setParameter($placeholder, (float) $value, \PDO::PARAM_INT);
                break;
            case 'boolean':
                $this->builder->setParameter($placeholder, $value == 'true' ? true : false, \PDO::PARAM_BOOL);
                break;
            default:
                $this->builder->setParameter($placeholder, $value, \PDO::PARAM_STR);
                break;
        }
    }

    /**
     * @param string $placeholder
     * @param RuleInterface $rule
     */
    public function setParameter($placeholder, RuleInterface $rule)
    {
        $this->setParameterByType($placeholder, $rule->getValue()->getValue(), $rule->getType());
    }
}