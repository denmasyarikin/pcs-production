<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

class AdjustmentQuantityConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'adjustment_quantity';

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = [
        'type' => ['discount', 'markup'],
        'relativity' => ['unit_price', 'unit_total'],
        'relativity_state' => ['initial', 'calculated'],
        'value' => 'integer',
        'quantity' => 'integer',
        'operator' => ['>', '>=', '=', '<', '<='],
        'rule' => ['fixed', 'percentage'],
    ];

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

        if ('calculated' === $structure['relativity_state'] and !is_null($this->prevCalculation)) {
            $unitPrice = $this->prevCalculation['unit_price'];
            $unitTotal = $this->prevCalculation['unit_total'];
        }

        $adjustment = 0;
        $initialUnitPrice = $unitPrice;
        $initialUnitTotal = $unitTotal;
        $relativeValue = $this->getRelativeValue($unitPrice, $unitTotal);

        if ($this->inCondition($structure['quantity'], $structure['operator'], $quantity)) {
            $calValue = $structure['value'];

            if ('percentage' === $structure['rule']) {
                $calValue = ceil(($relativeValue * $structure['value']) / 100);
            }

            if ('unit_total' === $structure['relativity']) {
                if ('discount' === $structure['type']) {
                    $unitTotal = $adjustment = $relativeValue -= $calValue;
                }

                if ('markup' === $structure['type']) {
                    $unitTotal = $adjustment = $relativeValue += $calValue;
                }
            }

            if ('unit_price' === $structure['relativity']) {
                if ('discount' === $structure['type']) {
                    $unitPrice = $adjustment = $relativeValue -= $calValue;
                }

                if ('markup' === $structure['type']) {
                    $unitPrice = $adjustment = $relativeValue += $calValue;
                }

                $unitTotal = $unitPrice * $quantity;
            }
        }

        return [
            'id' => $this->serviceOptionConfiguration->id,
            'name' => $this->serviceOptionConfiguration->name,
            'type' => $this->serviceOptionConfiguration->type,
            'structure' => $this->serviceOptionConfiguration->structure,
            'value' => $value,
            'quantity' => $quantity,
            'adjustment' => $adjustment,
            'unit_price' => $unitPrice,
            'unit_total' => $unitTotal,
            'initial' => [
                'unit_price' => $initialUnitPrice,
                'unit_total' => $initialUnitTotal,
            ],
        ];
    }

    /**
     * check is value in condition.
     *
     * @param int    $quantity
     * @param string $operator
     * @param int    $value
     *
     * @return bool
     */
    protected function inCondition($quantity, $operator, int $value)
    {
        switch ($operator) {
            case '>':
                return $value > $quantity;
                break;

            case '>=':
                return $value >= $quantity;
                break;

            case '=':
                return $value == $quantity;
                break;

            case '<':
                return $value < $quantity;
                break;

            case '<=':
                return $value <= $quantity;
                break;
        }
    }
}
