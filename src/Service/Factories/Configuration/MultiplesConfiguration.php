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
        'after_quantity' => 'integer',
        'input_multiples' => 'boolean',
        'input_min' => 'integer',
        'input_max' => 'integer',
        'input_default' => 'integer',
        'rule' => ['fixed', 'percentage'],
        'value' => 'integer',
        'enabled_back_forth' => 'boolean',
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

        if ($structure['enabled_back_forth']) {
            if (!is_array($value)) {
                throw new InvalidArgumentException('Not an array while enbabled back forth');
            }

            if (!array_key_exists('value', $value) || !array_key_exists('back_forth', $value)) {
                throw new InvalidArgumentException('array not contain key value or back_forth');
            }

            $value = $value['value'];
        }

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

        if ($structure['enabled_back_forth']) {
            $backForth = $value['back_forth'];
            $value = $value['value'];

            if ($backForth) {
                $structure['value'] *= 2;
                $structure['after_quantity'] = ceil($structure['after_quantity'] / 2);
            }
        }

        $initialUnitPrice = $unitPrice;
        $initialUnitTotal = $unitTotal;
        $firstPrice = $this->getRelativeValue($unitPrice, $unitTotal);
        $nextPrice = $this->getNextPrice($structure['rule'], $structure['value'], $firstPrice);

        // calculate multiple
        if ($structure['input_multiples']) {
            $unitTotal = $quantity * $firstPrice;
            $mulQty = $value - (float) $structure['after_quantity'];
            $multiples = $value;
            // calculate price
            if ($multiples > (float) $structure['after_quantity']) {
                $unitTotal += $nextPrice * $mulQty;
            }
        } else {
            $unitTotal = $firstPrice;
            $mulQty = $quantity - (float) $structure['after_quantity'];
            $multiples = ceil($mulQty / $structure['multiples']);
            // calculate price
            if ($quantity > (float) $structure['after_quantity']) {
                $unitTotal += $nextPrice * $multiples;
            }
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
            'multiples' => $multiples,
            'first_price' => $firstPrice,
            'next_price' => $nextPrice,
            'initial' => [
                'unit_price' => $initialUnitPrice,
                'unit_total' => $initialUnitTotal,
            ],
        ];
    }

    /**
     * get next price.
     *
     * @param string $rule
     * @param float    $value
     * @param float  $firstPrice
     *
     * @return float
     */
    protected function getNextPrice($rule, float $value, float $firstPrice)
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
