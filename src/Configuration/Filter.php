<?php

namespace XTAIN\FilterQueryBuilder\Configuration;

class Filter
{
    const ORDER_NONE = 'none';

    const ORDER_DEFAULT = 'default';

    const ORDER_MAP_ALPHANUM = 'map_alphanum';

    const ORDER_MAP_FIXED = 'map_fixed';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $label;

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
    protected $placeholder;

    /**
     * @var \string[]
     */
    protected $values;

    /**
     * @var \string[]
     */
    protected $operators;

    /**
     * @var \string[]
     */
    protected $validation;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var integer
     */
    protected $order = self::ORDER_DEFAULT;

    /**
     * @var string[]|callable
     */
    protected $orderValues;

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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
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
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }

    /**
     * @return \string[]
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param \string[] $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * @return \string[]
     */
    public function getOperators()
    {
        return $this->operators;
    }

    /**
     * @param \string[] $operators
     */
    public function setOperators($operators)
    {
        $this->operators = $operators;
    }

    /**
     * @return \string[]
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @param \string[] $validation
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return callable|\string[]
     */
    public function getOrderValues()
    {
        if ($this->orderValues === null) {
            return $this->values;
        }

        return $this->orderValues;
    }

    /**
     * @param callable|\string[] $orderValues
     */
    public function setOrderValues($orderValues)
    {
        $this->orderValues = $orderValues;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }
}
