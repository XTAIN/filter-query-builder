<?php

namespace XTAIN\FilterQueryBuilder;

use Doctrine\ORM\QueryBuilder;

interface BuilderInterface
{
    /**
     * @param Rule $rule
     *
     * @return void
     */
    public function apply(Rule $rule);

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder();

    /**
     * @param string $prefix
     *
     * @return string
     */
    public function createPlaceholder($prefix);

    /**
     * @param string $placeholder
     * @param RuleInterface $rule
     */
    public function setParameter($placeholder, RuleInterface $rule);
}