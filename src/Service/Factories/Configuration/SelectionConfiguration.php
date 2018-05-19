<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class SelectionConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'selection';

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = [
        'value' => 'array',
        'multiple' => 'boolean',
        'affected_the_price' => 'boolean',
        'relativity' => [null, 'unit_price', 'unit_total'],
        'rule' => [null, 'fixed', 'percentage'],
        'formula' => [null, 'multiplication', 'division', 'addition', 'reduction'],
    ];

    /**
     * is valid structure.
     *
     * @param array $config
     *
     * @return bool
     */
    public function isValidStructure(array $config)
    {
        return parent::isValidStructure($config)
            and $this->isValidAffectedThePriceStructure($config);
    }

    /**
     * check is valid affected the price structure.
     *
     * @param array $config
     *
     * @return bool
     */
    protected function isValidAffectedThePriceStructure(array $config)
    {
        if ($config['affected_the_price']) {
            if (is_null($config['relativity']) or is_null($config['rule']) or is_null($config['formula'])) {
                return false;
            }

            foreach ($config['value'] as $value) {
                if (!is_array($value)) {
                    return false;
                }

                if (!isset($value['label']) or !isset($value['value']) or !is_int($value['value'])) {
                    return false;
                }
            }
        } else {
            foreach ($config['value'] as $value) {
                if (!is_string($value)) {
                    return false;
                }
            }

        }

        return true;
    }

    /**
     * is validate value.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isValidValue($value) {
        parent::isValidValue($value);

        $configuration = $this->serviceTypeConfiguration->configuration;

        if ($configuration['affected_the_price']) {
            if (!is_array($value)) {
                throw new InvalidArgumentException('value is not array');
            }

            if ($configuration['multiple']) {
                foreach ($value as $val) {
                    if (!is_array($val)) {
                        throw new InvalidArgumentException('value is not array of array');
                    }

                    if (!isset($val['label']) OR !isset($val['value'])) {
                        throw new InvalidArgumentException('value or lable is not persent');
                    }

                    if (!in_array($val, $configuration['value'])) {
                        throw new InvalidArgumentException('value is not in selection');
                    }
                }
            } else {
                if (!isset($value['label']) OR !isset($value['value'])) {
                    throw new InvalidArgumentException('value or lable is not persent');
                }

                if (!in_array($value, $configuration['value'])) {
                    throw new InvalidArgumentException('value is not in selection');
                }
            }
        } else {
            if ($configuration['multiple']) {
                if (!is_array($value)) {
                    throw new InvalidArgumentException('value is not array');
                }
                foreach ($value as $val) {
                    if (!is_string($val)) {
                        throw new InvalidArgumentException('value is not array of string');
                    }

                    if (!in_array($val, $configuration['value'])) {
                        throw new InvalidArgumentException('value is not in selection');
                    }
                }
            } else {
                if (!is_string($value)) {
                    throw new InvalidArgumentException('value is not string');
                }

                if (!in_array($value, $configuration['value'])) {
                    throw new InvalidArgumentException('value is not in selection');
                }
            }
        }

        return true;
    }

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

        if ($config['affected_the_price']) {
            if ($config['multiple']) {
                foreach ($value as $val) {
                    $this->calculateSelected($val['value'], $config, $quantity, $unitPrice, $unitTotal);
                }
            } else {
                $this->calculateSelected($value['value'], $config, $quantity, $unitPrice, $unitTotal);
            }
        }

        return [
            'quantity' => $quantity,
            'before_unit_price' => $beforeUnitPrice,
            'before_unit_total' => $beforeUnitTotal,
            'after_unit_price' => $unitPrice,
            'after_unit_total' => $unitTotal,
            'selected' => $value,
            'configuration' => $this->serviceTypeConfiguration->toArray()
        ];
    }

    /**
     * calculate selected
     *
     * @param mixed $value
     * @param array $config 
     * @param mixed $value
     * @param int $quantity
     * @param int $unitPrice
     * @param int $unitTotal
     */
    protected function calculateSelected($value, array $config, int &$quantity, int &$unitPrice, int &$unitTotal)
    {
        if (!method_exists($this, $methode = $config['formula'])) {
            throw new InvalidArgumentException('Unknwon formula ' . $config['formula']);            
        }

        $relativeValue = $config['relativity'] === 'unit_price' ? $unitPrice : $unitTotal;

        if ($config['rule'] === 'percentage') {
            $value = ceil($relativeValue * $value) / 100;
        }

        $unitPrice = $this->$methode($value, $relativeValue);

        if ($config['relativity'] === 'unit_price') {
            $unitTotal = $quantity * $unitPrice;
        }
    }

    /**
     * multiplication
     *
     * @param int $value
     * @param int $relativeValue
     * 
     * @return int
     */
    public function multiplication($value, $relativeValue)
    {
        return $value * $relativeValue;
    }

    /**
     * division
     *
     * @param int $value
     * @param int $relativeValue
     * 
     * @return int
     */
    public function division($value, $relativeValue)
    {
        return $value / $relativeValue;
    }

    /**
     * addition
     *
     * @param int $value
     * @param int $relativeValue
     * 
     * @return int
     */
    public function addition($value, $relativeValue)
    {
        return $value + $relativeValue;
    }

    /**
     * reduction
     *
     * @param int $value
     * @param int $relativeValue
     * 
     * @return int
     */
    public function reduction($value, $relativeValue)
    {
        return $value - $relativeValue;
    }

}
