<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

interface ConfigurationInterface
{
    /**
     * apply configuration.
     *
     * @param mixed $value
     * @param float   $quantity
     * @param float   $unitPrice
     * @param float   $unitTotal
     *
     * @return array
     */
    public function apply($value, float $quantity, float $unitPrice, float &$unitTotal);

    /**
     * set previous calculation.
     *
     * @param array $prevCalculation
     */
    public function setPreviousCalculation(array $prevCalculation);

    /**
     * get relative value.
     *
     * @param float $unitPrice
     * @param float $unitTotal
     *
     * @return float
     */
    public function getRelativeValue(float $unitPrice, float $unitTotal);

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
