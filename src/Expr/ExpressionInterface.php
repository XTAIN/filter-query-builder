<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use XTAIN\FilterQueryBuilder\RuleInterface;

interface ExpressionInterface extends ChainInterface
{
    /**
     * @param RuleInterface $rule
     *
     * @return bool
     */
    public static function supports(RuleInterface $rule);

}