<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;

abstract class AbstractConjunction implements ConjunctionInterface
{
    /**
     * @var BuilderInterface
     */
    protected $builder;

    /**
     * @var ChainInterface[]
     */
    protected $children = array();

    /**
     * EqualHandler constructor.
     *
     * @param BuilderInterface $builder
     */
    public function __construct(
        BuilderInterface $builder
    ) {
        $this->builder = $builder;
    }

    /**
     * @param ChainInterface $child
     *
     * @return void
     */
    public function add(ChainInterface $child)
    {
        $this->children[] = $child;
    }
}