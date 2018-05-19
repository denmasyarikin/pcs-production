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

        $configuration = $this->serviceTypeConfiguration->configuration;

        if (!is_int($value)) {
            throw new InvalidArgumentException('Not an integer');
        }

        if ($value < $configuration['min']) {
            throw new InvalidArgumentException('Less then '.$configuration['min']);
        }

        if ($value > $configuration['max']) {
            throw new InvalidArgumentException('More then '.$configuration['max']);
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
    public function apply($value, int &$quantity, int &$unitPrice, int &$unitTotal)
    {
        $beforeUnitPrice = $unitPrice;
        $beforeUnitTotal = $unitTotal;
        $config = $this->serviceTypeConfiguration->configuration;

        if ('unit_total' === $config['relativity']) {
            $unitTotal *= $value;
        }

        if ('unit_price' === $config['relativity']) {
            $unitPrice *= $value;
            $unitTotal = $unitPrice * $quantity;
        }

        return [
            'quantity' => $quantity,
            'before_unit_price' => $beforeUnitPrice,
            'before_unit_total' => $beforeUnitTotal,
            'after_unit_price' => $unitPrice,
            'after_unit_total' => $unitTotal,
            'configuration' => $this->serviceTypeConfiguration->toArray(),
        ];
    }
}
