<?php

namespace Denmasyarikin\Production\Service\Factories;

use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;

class ConfigurationManager
{
    /**
     * configuration.
     *
     * @var array
     */
    protected $configurations = [
        Configuration\MultiplesConfiguration::class,
        Configuration\MultiplicationConfiguration::class,
        Configuration\DimensionAreaConfiguration::class,
        Configuration\DimensionVolumeConfiguration::class,
        Configuration\SelectionConfiguration::class,
    ];

    /**
     * configuration instance.
     *
     * @var array
     */
    protected $configurationInstances;

    /**
     * Create a new ConfigurationManager instance.
     */
    public function __construct()
    {
        $this->instantiateConfigurations();
    }

    /**
     * instantiate configurations.
     */
    protected function instantiateConfigurations()
    {
        foreach ($this->configurations as $configuration) {
            $instance = new $configuration();
            $this->configurationInstances[$instance->getType()] = $instance;
        }
    }

    /**
     * get configuration instances.
     *
     * @return array
     */
    public function getConfigurationInstances()
    {
        return $this->configurationInstances;
    }

    /**
     * get configuration instance.
     *
     * @param string $type
     *
     * @return mixed
     */
    public function getConfigurationInstance($type)
    {
        if ($this->isConfigurationExists($type)) {
            return $this->configurationInstances[$type];
        }
    }

    /**
     * check is configuration exists.
     *
     * @param string $type
     *
     * @return bool
     */
    public function isConfigurationExists($type)
    {
        return isset($this->configurationInstances[$type]);
    }

    /**
     * get value from request.
     *
     * @param ServiceType              $serviceType
     * @param ServiceTypeConfiguration $serviceTypeConfiguration
     * @param mixed                    $value
     *
     * @return data type
     */
    public function getValueFromRequest(ServiceType $serviceType, ServiceTypeConfiguration $serviceTypeConfiguration, $value)
    {
        $configurations = $serviceType->serviceTypeConfigurations;

        if (0 === $configurations->count()) {
            return null;
        }

        if (1 === $configurations->count()) {
            return $value;
        }

        if (isset($value[$serviceTypeConfiguration->id])) {
            return $value[$serviceTypeConfiguration->id];
        }
    }
}
