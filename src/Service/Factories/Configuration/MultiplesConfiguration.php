<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use App\Manager\Facades\Money;

class MultiplesConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'multiples';

    /**
     * need input.
     *
     * @var bool
     */
    protected $needInput = false;

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = [
        'relativity' => ['unit_price', 'unit_total'],
        'multiples' => 'integer',
        'rule' => ['fixed', 'percentage'],
        'value' => 'integer',
    ];

    /**
     * apply configuration.
     *
     * @param mixed $value
     * @param int $quantity
     * @param int $unitPrice
     * @param int $unitTotal
     * 
     * @return array
     */
    public function apply($value, int &$quantity, int &$unitPrice, int &$unitTotal)
    {
        $beforeUnitPrice = $unitPrice;
        $beforeUnitTotal = $unitTotal;
        $config = $this->serviceTypeConfiguration->configuration;
        $firstPrice = $unitPrice;
        $nextPrice = $this->getNextPrice($config['rule'], $config['value'], $config['relativity'] === 'unit_price' ? $unitPrice : $unitTotal);

        // calculate multiple
        $multiples = ceil($quantity / $config['multiples']);

        // calculate price
        $unitTotal = $firstPrice;
        $unitTotal += $nextPrice * ($multiples - 1);

        return [
            'quantity' => $quantity,
            'before_unit_price' => $beforeUnitPrice,
            'before_unit_total' => $beforeUnitTotal,
            'after_unit_price' => $unitPrice,
            'after_unit_total' => $unitTotal,
            'multiples' => $multiples,
            'first_price' => $firstPrice,
            'next_price' => $nextPrice,
            'configuration' => $this->serviceTypeConfiguration->toArray()
        ];
    }

    /**
     * get next price
     *
     * @param string $rule
     * @param int $value
     * @param int $relativeValue
     *
     * @return int
     */
    protected function getNextPrice($rule, int $value, int $relativeValue)
    {
        switch ($rule) {
            case 'fixed':
                return $value;
                break;

            case 'percentage':
                return Money::round($value * $relativeValue) / 100;
                break;
        }
    }
}
