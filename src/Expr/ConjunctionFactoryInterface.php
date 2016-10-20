<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\UnsupportedConjunctionException;

/**
 * Class ConjunctionFactoryInterface
 *
 * @package XTAIN\FilterQueryBuilder\Expr
 */
interface ConjunctionFactoryInterface
{
    /**
     * @param BuilderInterface $builder
     *
     * @return ConjunctionInterface[]
     */
    public function createConjunctions(
        BuilderInterface $builder
    );

    /**
     * @param BuilderInterface $builder
     * @param string           $queryCondition
     *
     * @return ConjunctionInterface
     * @throws UnsupportedConjunctionException
     */
    public function createSupportingConjunction(
        BuilderInterface $builder,
        $queryCondition
    );
}