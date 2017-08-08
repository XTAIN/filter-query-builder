<?php

namespace XTAIN\FilterQueryBuilder;

class Configuration
{
    /**
     * @var Configuration\Filter[]
     */
    protected $filters;

    /**
     * @return Configuration\Filter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param Configuration\Filter[] $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @param Configuration\Filter $filter
     */
    public function addFilter(Configuration\Filter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * @param string $id
     *
     * @return Configuration\Filter|null
     */
    public function getFilterById($id)
    {
        foreach ($this->filters as $filter) {
            if ($filter->getId() == $id) {
                return $filter;
            }
        }
    }
}
