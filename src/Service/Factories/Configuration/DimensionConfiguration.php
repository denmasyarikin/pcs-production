<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use App\Manager\Facades\Money;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;
use Symfony\Component\Console\Exception\InvalidArgumentException;

abstract class DimensionConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * input
     *
     * @var array
     */
    private $input = ['min', 'max', 'default'];

    /**
     * dimension
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
        'relativity' => ['unit_price', 'unit_total']
    ];

    /**
     * Create a new DimensionConfiguration instance.
     */
    public function __construct(ServiceTypeConfiguration $serviceTypeConfiguration = null)
    {
        parent::__construct($serviceTypeConfiguration);

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

        $structure = $this->serviceTypeConfiguration->structure;

        if (!is_array($value)) {
            throw new InvalidArgumentException('Not array');
        }

        foreach ($this->dimension as $dimension) {
            if (!isset($value[$dimension]) or !is_int($value[$dimension])) {
                throw new InvalidArgumentException(ucwords($dimension) . ' Not present or not integer');
            }

            $min = $structure['min_'.$dimension];

            if ($value[$dimension] < $min) {
                throw new InvalidArgumentException('Width Less then ' . $min);
            }

            $max = $structure['max_'.$dimension];

            if ($value[$dimension] > $max) {
                throw new InvalidArgumentException('Width More then ' . $max);
            }
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
        $structure = $this->serviceTypeConfiguration->structure;

        if ('unit_total' === $structure['relativity']) {
            foreach ($this->dimension as $dimension) {
                $unitTotal *= $value[$dimension];
            }
        }

        if ('unit_price' === $structure['relativity']) {
            foreach ($this->dimension as $dimension) {
                $unitPrice *= $value[$dimension];
            }
            
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
