<?php

namespace XTAIN\FilterQueryBuilder;

use XTAIN\FilterQueryBuilder\Configuration;

interface FilterProviderInterface
{
    /**
     * @param Configuration $configuration
     */
    public function process(Configuration $configuration);
}