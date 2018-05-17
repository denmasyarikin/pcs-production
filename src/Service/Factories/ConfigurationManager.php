<?php

namespace Denmasyarikin\Production\Service\Factories;

use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServicePrice;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class ConfigurationManager
{
    /**
     * service type
     *
     * @var ServiceType
     */
    protected $serviceType;

    /**
     * configuration.
     *
     * @var array
     */
    protected $configurations = [
        Configuration\IncreasementConfiguration::class,
        Configuration\MultiplicationConfiguration::class,
        Configuration\MultiplicationAreaConfiguration::class,
        Configuration\MultiplicationVolumeConfiguration::class,
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
     *
     * @param ServiceType $serviceType
     */
    public function __construct(ServiceType $serviceType = null)
    {
        $this->serviceType = $serviceType;
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
     * calculate price
     *
     * @param int $quantity
     * @param mixed $value
     * @param int $chanelId
     * @return array
     */
    public function calculatePrice($quantity, $value, $chanelId = null)
    {
        if (is_null($this->serviceType)) {
            throw new InvalidArgumentException('Service type is undefined');
        }

        $basePrice = $this->getBasePrice($chanelId);
        $configurations = $this->serviceType->serviceTypeConfigurations;
        
        switch (true) {
            case $configurations->count() === 0:
                return $this->callculateWithNoConfiguration($quantity, $value, $basePrice);
                break;

            case $configurations->count() === 1:
                return $this->callculateSingularConfiguration($quantity, $value, $basePrice);
                break;

            default:
                return $this->callculateMultipleConfiguration($quantity, $value, $basePrice);
                break;
        }
    }

    /**
     * get base price
     *
     * @param int $chanelId
     *
     * @return ServicePrice
     */
    protected function getBasePrice($chanelId = null)
    {
        $query = $this->serviceType->servicePrices();
 
        if (is_null($chanelId)) {
            $query->whereNull('chanel_id');
        } else {
            $query->whereChanelId($chanelId);
        }

        $serviceType = $query->first();

        if (is_null($serviceType)) {
            throw new InvalidArgumentException('No base price found');
        }

        return $serviceType;
    }
    
    /**
     * calculate with no configuration
     *
     * @param int $quantity
     * @param mixed $value
     * @param ServicePrice $basePrice
     *
     * @return array
     */
    public function callculateWithNoConfiguration($quantity, $value, ServicePrice $basePrice)
    {
        return [];
    }

    /**
     * calculate singular configuration
     *
     * @param int $quantity
     * @param mixed $value
     * @param ServicePrice $basePrice
     *
     * @return array
     */
    public function callculateSingularConfiguration($quantity, $value, ServicePrice $basePrice)
    {
        return 'singular';
    }

    /**
     * calculate multiple configuration
     *
     * @param int $quantity
     * @param mixed $value
     * @param ServicePrice $basePrice
     *
     * @return array
     */
    public function callculateMultipleConfiguration($quantity, $value, ServicePrice $basePrice)
    {
        return [];
    }
}
