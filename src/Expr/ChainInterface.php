<?php

namespace XTAIN\FilterQueryBuilder\Expr;

interface ChainInterface
{
    /**
     * @return mixed
     */
    public function getQueryExpression();
}