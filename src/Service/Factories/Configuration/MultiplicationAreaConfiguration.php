<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class MultiplicationAreaConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'multiplication_area';

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = [
        'relativity' => ['unit_price', 'unit_total'],
        'min_width' => 'integer',
        'max_width' => 'integer',
        'min_length' => 'integer',
        'max_length' => 'integer',
        'default_width' => 'integer',
        'default_length' => 'integer',
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

        if (!is_array($value)) {
            throw new InvalidArgumentException('Not array');
        }

        if (!isset($value['width'])) {
            throw new InvalidArgumentException('Width Not present');
        }

        if (!isset($value['length'])) {
            throw new InvalidArgumentException('Length Not present');
        }

        if ($value['width'] < $configuration['min_width']) {
            throw new InvalidArgumentException('Width Less then '.$configuration['min_width']);
        }

        if ($value['width'] > $configuration['max_width']) {
            throw new InvalidArgumentException('Width More then '.$configuration['max_width']);
        }

        if ($value['length'] < $configuration['min_length']) {
            throw new InvalidArgumentException('Length Less then '.$configuration['min_length']);
        }

        if ($value['length'] > $configuration['max_length']) {
            throw new InvalidArgumentException('Length More then '.$configuration['max_length']);
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
            $unitTotal *= $value['width'] * $value['length'];
        }

        if ('unit_price' === $config['relativity']) {
            $unitPrice *= $value['width'] * $value['length'];
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
