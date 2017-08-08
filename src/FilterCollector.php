<?php

namespace XTAIN\FilterQueryBuilder;

use XTAIN\FilterQueryBuilder\Configuration;

/**
 * Class FilterCollector
 * @package XTAIN\FilterQueryBuilder
 */
class FilterCollector
{
    /**
     * @var FilterProviderInterface[]
     */
    protected $providers;

    /**
     * @param FilterProviderInterface $filterProvider
     * @param integer                 $priority
     */
    public function registerProvider(FilterProviderInterface $filterProvider, $priority = 100)
    {
        $this->providers[] = array(
            $priority,
            $filterProvider
        );
    }

    /**
     * @return FilterProviderInterface[]
     */
    protected function getProviders()
    {
        $providers = $this->providers;

        usort($providers, function($a, $b) {
            list($priorityA, $providerA) = $a;
            list($priorityB, $providerB) = $b;

            if ($priorityA == $priorityB) {
                return 0;
            }

            return ($priorityA < $priorityB) ? 1 : -1;
        });

        $providersList = array();

        foreach ($providers as $handler) {
            $providersList[] = $handler[1];
        }

        return $providersList;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        $configuration = new Configuration();

        foreach ($this->getProviders() as $provider) {
            $provider->process($configuration);
        }

        return $configuration;
    }
}