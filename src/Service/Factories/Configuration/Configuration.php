<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

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
                return false;
            }

            if (!$this->isValidStructureValue($config[$key], $structure)) {
                return false;
            }
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
            return in_array($value, $structure);
        }

        switch ($structure) {
            case 'integer':
                return is_int($value);
                break;

            case 'string':
                return is_string($value);
                break;

            case 'array':
                return is_array($value);
                break;

            case 'boolean':
                return is_bool($value);
                break;

            default:
                return true;
                break;
        }
    }
}
