<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use App\Manager\Facades\Money;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class MultiplesConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'multiples';

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = [
        'relativity' => ['unit_price', 'unit_total'],
        'multiples' => 'integer',
        'input_multiples' => 'boolean',
        'input_min' => 'integer',
        'input_max' => 'integer',
        'input_default' => 'integer',
        'rule' => ['fixed', 'percentage'],
        'value' => 'integer',
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

        $structure = $this->serviceTypeConfiguration->structure;

        if ($structure['input_multiples']) {
            if (!is_int($value)) {
                throw new InvalidArgumentException('Not an integer');
            }

            if ($value < $structure['input_min']) {
                throw new InvalidArgumentException('Less then '.$structure['input_min']);
            }

            if ($value > $structure['input_max']) {
                throw new InvalidArgumentException('More then '.$structure['input_max']);
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
        $firstPrice = $unitPrice;
        $nextPrice = $this->getNextPrice($structure['rule'], $structure['value'], 'unit_price' === $structure['relativity'] ? $unitPrice : $unitTotal);

        // calculate multiple
        if ($structure['input_multiples']) {
            $multiples = $value;
        } else {
            $multiples = ceil($quantity / $structure['multiples']);
        }

        // calculate price
        $unitTotal = $firstPrice;
        $unitTotal += $nextPrice * ($multiples - 1);

        if ($structure['input_multiples']) {
            $unitTotal *= $quantity;
        }

        return [
            'quantity' => $quantity,
            'before_unit_price' => $beforeUnitPrice,
            'before_unit_total' => $beforeUnitTotal,
            'after_unit_price' => $unitPrice,
            'after_unit_total' => $unitTotal,
            'multiples' => $multiples,
            'first_price' => $firstPrice,
            'next_price' => $nextPrice,
            'configuration' => $this->serviceTypeConfiguration->toArray(),
        ];
    }

    /**
     * get next price.
     *
     * @param string $rule
     * @param int    $value
     * @param int    $relativeValue
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
