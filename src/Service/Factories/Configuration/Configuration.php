<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use Denmasyarikin\Production\Service\ServiceTypeConfiguration;
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
     * service type configuration.
     *
     * @var ServiceTypeConfiguration
     */
    public $serviceTypeConfiguration;

    /**
     * Create a new Configuration instance.
     */
    public function __construct(ServiceTypeConfiguration $serviceTypeConfiguration = null)
    {
        $this->serviceTypeConfiguration = $serviceTypeConfiguration;
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
                throw new InvalidArgumentException($key . ' not present');
            }

            $this->isValidStructureValue($config[$key], $structure);
        }

        return true;
    }

    /**
     * check is structure value valid.
     *
     * @param mixed $value
     * @param mixed $structure
     *
     * @return bool
     */
    protected function isValidStructureValue($value, $structure)
    {
        if (is_array($structure)) {
            if  (!in_array($value, $structure)) {
                throw new InvalidArgumentException($value . ' tidak dalam pilihan');
            }
        }

        switch ($structure) {
            case 'integer':
                if (!is_int($value)) {
                    throw new InvalidArgumentException($value . ' bukan integer');
                }
                break;

            case 'string':
                if (!is_string($value)) {
                    throw new InvalidArgumentException($value . ' bukan string');
                }
                break;

            case 'array':
                if (!is_array($value)) {
                    throw new InvalidArgumentException($value . ' bukan array');
                }
                break;

            case 'boolean':
                if (!is_bool($value)) {
                    throw new InvalidArgumentException($value . ' bukan boolean');
                }
                break;

            default:
                return true;
                break;
        }
    }

    /**
     * set service type configuration.
     *
     * @return ServiceTypeConfiguration
     */
    public function setServiceTypeConfiguration(ServiceTypeConfiguration $serviceTypeConfiguration)
    {
        return $this->serviceTypeConfiguration = $serviceTypeConfiguration;
    }

    /**
     * get service type configuration.
     *
     * @return ServiceTypeConfiguration
     */
    public function getServiceTypeConfiguration()
    {
        return $this->serviceTypeConfiguration;
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
        if (is_null($this->serviceTypeConfiguration)) {
            return false;
        }

        return true;
    }
}
