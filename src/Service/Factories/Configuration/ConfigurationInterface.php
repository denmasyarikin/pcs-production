<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

interface ConfigurationInterface
{
    /**
     * apply configuration.
     *
     * @param mixed $value
     * @param int   $quantity
     * @param int   $unitPrice
     * @param int   $unitTotal
     *
     * @return array
     */
    public function apply($value, int $quantity, int $unitPrice, int &$unitTotal);

    /**
     * configuration type.
     *
     * @return string
     */
    public function getType();

    /**
     * configuration structure.
     *
     * @return string
     */
    public function getStructure();

    /**
     * is valid structure.
     *
     * @param array $config
     *
     * @return bool
     */
    public function isValidStructure(array $config);

    /**
     * is validate value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isValidValue($value);
}
