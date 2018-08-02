<?php

namespace Denmasyarikin\Production\Service\Factories;

use Denmasyarikin\Production\Service\ServiceOptionConfiguration;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class Calculation
{
    /**
     * manager.
     *
     * @var ConfigurationManager
     */
    protected $manager;

    /**
     * quantity.
     *
     * @var int
     */
    protected $quantity;

    /**
     * unit price.
     *
     * @var int
     */
    protected $unitPrice;

    /**
     * unit total.
     *
     * @var int
     */
    protected $unitTotal;

    /**
     * configurations.
     *
     * @var array
     */
    protected $configurations = [];

    /**
     * Create a new Calculation instance.
     *
     * @param float   $quantity
     * @param float $unitPrice
     */
    public function __construct(float $quantity, float $unitPrice)
    {
        $this->manager = new ConfigurationManager();
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->unitTotal = $quantity * $unitPrice;
    }

    /**
     * apply.
     *
     * @param ServiceOptionConfiguration $serviceOptionConfiguration
     * @param mixed                      $value
     *
     * @return data type
     */
    public function applyConfiguration(ServiceOptionConfiguration $serviceOptionConfiguration, $value)
    {
        $configInstance = $this->getConfigInstance($serviceOptionConfiguration);

        if (count($this->configurations) > 0) {
            $last = array_values(array_slice($this->configurations, -1))[0];
            $configInstance->setPreviousCalculation($last);
        }

        $configuration = $configInstance->apply($value, $this->quantity, $this->unitPrice, $this->unitTotal);

        $this->configurations[] = $configuration;
    }

    /**
     * get configuration instance.
     *
     * @param ServiceOptionConfiguration $serviceOptionConfiguration
     *
     * @return ConfigurationInterface
     */
    public function getConfigInstance(ServiceOptionConfiguration $serviceOptionConfiguration)
    {
        $instance = $this->manager->getConfigurationInstance($serviceOptionConfiguration->type);

        if (!is_null($instance)) {
            $instance->setServiceOptionConfiguration($serviceOptionConfiguration);

            return $instance;
        }

        throw new InvalidArgumentException('Configuration instance not available');
    }

    /**
     * get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * get unit price.
     *
     * @return int
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * get unit total.
     *
     * @return int
     */
    public function getUnitTotal()
    {
        return $this->unitTotal;
    }

    /**
     * get unit configuration.
     *
     * @return array
     */
    public function getConfigurations()
    {
        return $this->configurations;
    }
}
