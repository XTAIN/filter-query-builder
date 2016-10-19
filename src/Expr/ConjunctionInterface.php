<?php

namespace XTAIN\FilterQueryBuilder\Expr;

interface ConjunctionInterface extends ChainInterface
{
    /**
     * @param ChainInterface $child
     *
     * @return void
     */
    public function add(ChainInterface $child);

    /**
     * @param string $operation
     *
     * @return bool
     */
    public static function supports($operation);
}