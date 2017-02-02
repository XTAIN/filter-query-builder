<?php

namespace XTAIN\FilterQueryBuilder;

use XTAIN\FilterQueryBuilder\Rule\Query\Order;

class Query implements QueryInterface
{
    /**
     * @var Order[]
     */
    protected $order = [];

    /**
     * @var string
     */
    protected $condition = 'AND';

    /**
     * @var RuleInterface[]
     */
    protected $rules = [];

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return RuleInterface[]
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param RuleInterface[] $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return Rule\Query\Order[]
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Rule\Query\Order[] $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }
}