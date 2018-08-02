<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class MinimumPriceConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'minimum_total_price';

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = ['value' => 'integer'];

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
        $minimum = intval($structure['value']);

        $initialUnitPrice = $unitPrice;
        $initialUnitTotal = $unitTotal;

        if ($unitTotal < $minimum) $unitTotal = $minimum;

        return [
            'id' => $this->serviceOptionConfiguration->id,
            'name' => $this->serviceOptionConfiguration->name,
            'type' => $this->serviceOptionConfiguration->type,
            'structure' => $this->serviceOptionConfiguration->structure,
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
