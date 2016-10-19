<?php

namespace XTAIN\FilterQueryBuilder;

class Rule implements RuleInterface
{
    /**
     * @var string
     */
    protected $condition;

    /**
     * @var RuleInterface[]
     */
    protected $rules;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $input;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var Rule\Value
     */
    protected $value;

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
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param string $input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return Rule\Value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param Rule\Value $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}