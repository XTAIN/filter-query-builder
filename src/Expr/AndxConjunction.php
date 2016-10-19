<?php

namespace XTAIN\FilterQueryBuilder\Expr;

use Doctrine\ORM\Query\Expr\Andx;

class AndxConjunction extends AbstractConjunction implements ConjunctionInterface
{
    /**
     * @return mixed
     */
    public function getQueryExpression()
    {
        $expr = new Andx();

        foreach ($this->children as $child) {
            $expr->add(
                $child->getQueryExpression()
            );
        }

        return $expr;
    }

    /**
     * @param string $operation
     *
     * @return bool
     */
    public static function supports($operation)
    {
        return strtolower($operation) == 'and';
    }
}