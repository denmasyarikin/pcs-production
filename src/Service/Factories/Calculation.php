<?php

namespace Denmasyarikin\Production\Service\Factories;

use Denmasyarikin\Production\Service\ServiceTypeConfiguration;
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
    protected $configurations;

    /**
     * Create a new Calculation instance.
     *
     * @param param type $param
     */
    public function __construct(int $quantity, int $unitPrice)
    {
        $this->manager = new ConfigurationManager();
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->unitTotal = $quantity * $unitPrice;
    }

    /**
     * apply.
     *
     * @param ServiceTypeConfiguration $serviceTypeConfiguration
     * @param mixed                    $value
     *
     * @return data type
     */
    public function applyConfiguration(ServiceTypeConfiguration $serviceTypeConfiguration, $value)
    {
        $configInstance = $this->getConfigInstance($serviceTypeConfiguration);

        $configuration = $configInstance->apply($value, $this->quantity, $this->unitPrice, $this->unitTotal);

        $this->configurations = $configuration;
    }

    /**
     * get configuration instance.
     *
     * @param ServiceTypeConfiguration $serviceTypeConfiguration
     *
     * @return ConfigurationInterface
     */
    public function getConfigInstance(ServiceTypeConfiguration $serviceTypeConfiguration)
    {
        $instance = $this->manager->getConfigurationInstance($serviceTypeConfiguration->type);

        if (!is_null($instance)) {
            $instance->setServiceTypeConfiguration($serviceTypeConfiguration);

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
