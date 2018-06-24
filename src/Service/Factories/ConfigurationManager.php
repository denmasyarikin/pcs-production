<?php

namespace Denmasyarikin\Production\Service\Factories;

use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\ServiceOptionConfiguration;

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
        Configuration\AdjustmentQuantityConfiguration::class,
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
     * @param ServiceOption              $serviceOption
     * @param ServiceOptionConfiguration $serviceOptionConfiguration
     * @param mixed                      $value
     *
     * @return data type
     */
    public function getValueFromRequest(ServiceOption $serviceOption, ServiceOptionConfiguration $serviceOptionConfiguration, $value)
    {
        $configurations = $serviceOption->serviceOptionConfigurations;

        if (0 === $configurations->count()) {
            return null;
        }

        if (isset($value[$serviceOptionConfiguration->id])) {
            return $value[$serviceOptionConfiguration->id];
        }
    }
}
