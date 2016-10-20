<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\BuilderInterface;
use XTAIN\FilterQueryBuilder\UnsupportedConjunctionException;

/**
 * Class AbstractConjunctionFactory
 * @package XTAIN\FilterQueryBuilder\Expr
 */
abstract class AbstractConjunctionFactory implements ConjunctionFactoryInterface
{
    /**
     * @return string[]
     */
    abstract protected function getClasses();

    /**
     * @param BuilderInterface $builder
     *
     * @return ConjunctionInterface[]
     */
    public function createConjunctions(
        BuilderInterface $builder
    ) {
        $conjunctions = array();

        foreach ($this->getClasses() as $class) {
            $conjunctions[] = new $class($builder);
        }

        return $conjunctions;
    }

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
    ) {
        foreach ($this->getClasses() as $class) {
            if (call_user_func(array($class, 'supports'), $queryCondition)) {
                return $class($builder);
            }
        }

        throw new UnsupportedConjunctionException(
            sprintf(
                'Cannot find conjunction supporting %s',
                $queryCondition
            )
        );
    }
}