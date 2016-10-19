<?php

namespace XTAIN\FilterQueryBuilder;

interface RuleInterface
{
    /**
     * @return string
     */
    public function getCondition();

    /**
     * @return RuleInterface[]
     */
    public function getRules();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getField();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getInput();

    /**
     * @return string
     */
    public function getOperator();

    /**
     * @return Rule\Value
     */
    public function getValue();
}