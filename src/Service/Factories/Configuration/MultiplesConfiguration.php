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
        'relativity_state' => ['initial', 'calculated'],
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

        $structure = $this->serviceOptionConfiguration->structure;

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
    public function apply($value, int $quantity, int $unitPrice, int &$unitTotal)
    {
        $structure = $this->serviceOptionConfiguration->structure;

        if ($structure['relativity_state'] === 'calculated' AND !is_null($this->prevCalculation)) {
            $unitPrice = $this->prevCalculation['unit_price'];
            $unitTotal = $this->prevCalculation['unit_total'];
        }

        $initialUnitPrice = $unitPrice;
        $initialUnitTotal = $unitTotal;
        $firstPrice = $this->getRelativeValue($unitPrice, $unitTotal);
        $nextPrice = $this->getNextPrice($structure['rule'], $structure['value'], $firstPrice);

        // calculate multiple
        if ($structure['input_multiples']) {
            $unitTotal = $quantity * $firstPrice;
            $multiples = $value;
        } else {
            $unitTotal = $firstPrice;
            $multiples = ceil($quantity / $structure['multiples']);
        }

        // calculate price
        $unitTotal += $nextPrice * ($multiples - 1);

        return [
            'id' => $this->serviceOptionConfiguration->id,
            'name' => $this->serviceOptionConfiguration->name,
            'type' => $this->serviceOptionConfiguration->type,
            'structure' => $this->serviceOptionConfiguration->structure,
            'value' => $value,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'unit_total' => $unitTotal,
            'multiples' => $multiples,
            'first_price' => $firstPrice,
            'next_price' => $nextPrice,
            'initial' => [
                'unit_price' => $initialUnitPrice,
                'unit_total' => $initialUnitTotal
            ]
        ];
    }

    /**
     * get next price.
     *
     * @param string $rule
     * @param int    $value
     * @param int    $firstPrice
     *
     * @return int
     */
    protected function getNextPrice($rule, int $value, int $firstPrice)
    {
        switch ($rule) {
            case 'fixed':
                return $value;
                break;

            case 'percentage':
                return Money::round($value * $firstPrice) / 100;
                break;
        }
    }
}
