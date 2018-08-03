<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use Denmasyarikin\Production\Service\ServiceOptionConfiguration;
use Symfony\Component\Console\Exception\InvalidArgumentException;

abstract class DimensionConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * input.
     *
     * @var array
     */
    private $input = ['min', 'max', 'default'];

    /**
     * dimension.
     *
     * @var array
     */
    protected $dimension = [];

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = [
        'relativity' => ['unit_price', 'unit_total'],
        'relativity_state' => ['initial', 'calculated'],
    ];

    /**
     * Create a new DimensionConfiguration instance.
     */
    public function __construct(ServiceOptionConfiguration $serviceOptionConfiguration = null)
    {
        parent::__construct($serviceOptionConfiguration);

        foreach ($this->input as $input) {
            foreach ($this->dimension as $dimension) {
                $this->structure["{$input}_{$dimension}"] = 'integer';
            }
        }
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
        parent::isValidValue($value);

        $structure = $this->serviceOptionConfiguration->structure;

        if (!is_array($value)) {
            throw new InvalidArgumentException('Not array');
        }

        foreach ($this->dimension as $dimension) {
            if (!isset($value[$dimension]) or !is_numeric($value[$dimension])) {
                throw new InvalidArgumentException(ucwords($dimension).' Not present or not integer');
            }

            $min = $structure['min_'.$dimension];

            if ($value[$dimension] < $min) {
                throw new InvalidArgumentException('Width Less then '.$min);
            }

            $max = $structure['max_'.$dimension];

            if ($value[$dimension] > $max) {
                throw new InvalidArgumentException('Width More then '.$max);
            }
        }

        return true;
    }

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
    public function apply($value, float $quantity, float $unitPrice, float &$unitTotal)
    {
        $structure = $this->serviceOptionConfiguration->structure;

        if ('calculated' === $structure['relativity_state'] and !is_null($this->prevCalculation)) {
            $unitPrice = $this->prevCalculation['unit_price'];
            $unitTotal = $this->prevCalculation['unit_total'];
        }

        $initialUnitPrice = $unitPrice;
        $initialUnitTotal = $unitTotal;
        $relativeValue = $this->getRelativeValue($unitPrice, $unitTotal);

        if ('unit_total' === $structure['relativity']) {
            foreach ($this->dimension as $dimension) {
                $unitTotal = ($relativeValue *= $value[$dimension]);
            }
        }

        if ('unit_price' === $structure['relativity']) {
            foreach ($this->dimension as $dimension) {
                $unitPrice = ($relativeValue *= $value[$dimension]);
            }

            $unitTotal = $unitPrice * $quantity;
        }

        return [
            'id' => $this->serviceOptionConfiguration->id,
            'name' => $this->serviceOptionConfiguration->name,
            'type' => $this->serviceOptionConfiguration->type,
            'structure' => $this->serviceOptionConfiguration->structure,
            'value' => $value,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'unit_total' => $unitTotal,
            'initial' => [
                'unit_price' => $initialUnitPrice,
                'unit_total' => $initialUnitTotal,
            ],
        ];
    }
}
