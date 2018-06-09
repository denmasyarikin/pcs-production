<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class MultiplicationConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'multiplication';

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = [
        'relativity' => ['unit_price', 'unit_total'],
        'relativity_state' => ['initial', 'calculated'],
        'min' => 'integer',
        'max' => 'integer',
        'default' => 'integer',
    ];

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

        if (!is_int($value)) {
            throw new InvalidArgumentException('Not an integer');
        }

        if ($value < $structure['min']) {
            throw new InvalidArgumentException('Less then '.$structure['min']);
        }

        if ($value > $structure['max']) {
            throw new InvalidArgumentException('More then '.$structure['max']);
        }

        return true;
    }

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
    public function apply($value, int $quantity, int &$unitPrice, int &$unitTotal)
    {
        $initialUnitPrice = $unitPrice;
        $initialUnitTotal = $unitTotal;
        $structure = $this->serviceOptionConfiguration->structure;
        $relativeValue = $this->getRelativeValue($unitPrice, $unitTotal);

        if ('unit_total' === $structure['relativity']) {
            $unitTotal = ($relativeValue *= $value);
        }

        if ('unit_price' === $structure['relativity']) {
            $unitPrice = ($relativeValue *= $value);
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
                'unit_total' => $initialUnitTotal
            ]
        ];
    }
}
