<?php

namespace XTAIN\FilterQueryBuilder;

interface QueryInterface
{
    /**
     * @return string
     */
    public function getCondition();

    /**
     * @param string $condition
     */
    public function setCondition($condition);

    /**
     * @return RuleInterface[]
     */
    public function getRules();

    /**
     * @param RuleInterface[] $rules
     */
    public function setRules($rules);

    /**
     * @return Query\Order[]
     */
    public function getOrder();

    /**
     * @param Query\Order[] $order
     */
    public function setOrder($order);
}