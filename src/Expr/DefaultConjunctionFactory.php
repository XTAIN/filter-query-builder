<?php

namespace XTAIN\FilterQueryBuilder\Expr;

class DefaultConjunctionFactory extends AbstractConjunctionFactory
{
    /**
     * @return string[]
     */
    protected function getClasses()
    {
        return array(
            AndxConjunction::class,
            OrxConjunction::class
        );
    }
}