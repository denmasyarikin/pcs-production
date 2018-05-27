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
    public function apply($value, int $quantity, int $unitPrice, int &$unitTotal)
    {
        $beforeUnitPrice = $unitPrice;
        $beforeUnitTotal = $unitTotal;
        $structure = $this->serviceOptionConfiguration->structure;

        if ('unit_total' === $structure['relativity']) {
            $unitTotal *= $value;
        }

        if ('unit_price' === $structure['relativity']) {
            $unitPrice *= $value;
            $unitTotal = $unitPrice * $quantity;
        }

        return [
            'value' => $value,
            'quantity' => $quantity,
            'before_unit_price' => $beforeUnitPrice,
            'before_unit_total' => $beforeUnitTotal,
            'configuration' => $this->serviceOptionConfiguration->toArray(),
        ];
    }
}
