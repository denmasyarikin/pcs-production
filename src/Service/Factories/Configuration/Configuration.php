<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use Denmasyarikin\Production\Service\ServiceOptionConfiguration;
use Symfony\Component\Console\Exception\InvalidArgumentException;

abstract class Configuration
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type;

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = [];

    /**
     * service option configuration.
     *
     * @var ServiceOptionConfiguration
     */
    public $serviceOptionConfiguration;

    /**
     * previous calculation
     *
     * @var array
     */
    protected $prevCalculation;

    /**
     * Create a new Configuration instance.
     */
    public function __construct(ServiceOptionConfiguration $serviceOptionConfiguration = null)
    {
        $this->serviceOptionConfiguration = $serviceOptionConfiguration;
    }

    /**
     * set previous calculation
     *
     * @param array $prevCalculation
     */
    public function setPreviousCalculation(array $prevCalculation)
    {
        $this->prevCalculation = $prevCalculation;
    }

    /**
     * get relative value.
     *
     * @param int $unitPrice
     * @param int $unitTotal
     *
     * @return int
     */
    public function getRelativeValue(int $unitPrice, int $unitTotal)
    {
        $structure = $this->serviceOptionConfiguration->structure;

        if ('calculated' === $structure['relativity_state']) {
            return 'unit_price' === $structure['relativity']
                    ? $unitPrice
                    : $unitTotal;
        }

        $initial = $this->getInitialValue($unitPrice, $unitTotal);

        return 'unit_price' === $structure['relativity']
                ? $initial['unit_price']
                : $initial['unit_total'];
    }

    /**
     * get inital value
     *
     * @param int $unitPrice
     * @param int $unitTotal
     *
     * @return array
     */
    protected function getInitialValue(int $unitPrice, int $unitTotal)
    {
        $initial = [
            'unit_price' => $unitPrice,
            'unit_total' => $unitTotal
        ];

        if (is_array($this->prevCalculation) AND isset($this->prevCalculation['initial'])) {
            if (isset($this->prevCalculation['initial']['unit_price'])) {
                $initial['unit_price'] = $this->prevCalculation['initial']['unit_price'];
            }

            if (isset($this->prevCalculation['initial']['unit_total'])) {
                $initial['unit_total'] = $this->prevCalculation['initial']['unit_total'];
            }
        }

        return $initial;
    }

    /**
     * get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * configuration structure.
     *
     * @return string
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * is valid structure.
     *
     * @param array $config
     *
     * @return bool
     */
    public function isValidStructure(array $config)
    {
        foreach ($this->structure as $key => $structure) {
            if (!array_key_exists($key, $config)) {
                throw new InvalidArgumentException($key.' is not present');
            }

            $this->isValidStructureValue($key, $config[$key], $structure);
        }

        return true;
    }

    /**
     * check is structure value valid.
     *
     * @param mixed $key
     * @param mixed $value
     * @param mixed $structure
     *
     * @return bool
     */
    protected function isValidStructureValue($key, $value, $structure)
    {
        if (is_array($structure)) {
            if (!in_array($value, $structure)) {
                throw new InvalidArgumentException($key.' is not in options');
            }
        }

        switch ($structure) {
            case 'integer':
                if (!is_int($value)) {
                    throw new InvalidArgumentException($key.' is not integer');
                }
                break;

            case 'string':
                if (!is_string($value)) {
                    throw new InvalidArgumentException($key.' is not string');
                }
                break;

            case 'array':
                if (!is_array($value)) {
                    throw new InvalidArgumentException($key.' is not array');
                }
                break;

            case 'boolean':
                if (!is_bool($value)) {
                    throw new InvalidArgumentException($key.' is not boolean');
                }
                break;

            default:
                return true;
                break;
        }
    }

    /**
     * set service option configuration.
     *
     * @return ServiceOptionConfiguration
     */
    public function setServiceOptionConfiguration(ServiceOptionConfiguration $serviceOptionConfiguration)
    {
        return $this->serviceOptionConfiguration = $serviceOptionConfiguration;
    }

    /**
     * get service option configuration.
     *
     * @return ServiceOptionConfiguration
     */
    public function getServiceOptionConfiguration()
    {
        return $this->serviceOptionConfiguration;
    }

    /**
     * is validate value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isValidValue($value)
    {
        if (is_null($this->serviceOptionConfiguration)) {
            return false;
        }

        return true;
    }
}
