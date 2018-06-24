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
        'required' => 'boolean',
        'default' => 'any',
        'affected_the_price' => 'boolean',
        'relativity' => [null, 'unit_price', 'unit_total'],
        'relativity_state' => [null, 'initial', 'calculated'],
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
            and $this->isValidAdvanceStructure($config);
    }

    /**
     * check is valid advance structure.
     *
     * @param array $config
     *
     * @return bool
     */
    protected function isValidAdvanceStructure(array $config)
    {
        $this->checkStructureValue($config['value'], $config['affected_the_price']);

        $checkDuplicate = array_unique($config['value'], SORT_REGULAR);

        if (count($checkDuplicate) !== count($config['value'])) {
            throw new InvalidArgumentException('duplicate value is not allowed');
        }

        $this->checkStructureDefaultValue($config['default'], $config['required'], $config['multiple'], $config['affected_the_price']);

        if (!empty($config['default'])) {
            $this->checkisDefaultValueInSelection($config['default'], $config['value'], $config['multiple']);
        }

        if ($config['affected_the_price']
            and (empty($config['relativity'])
            or empty($config['rule'])
            or empty($config['formula']))) {
            throw new InvalidArgumentException('relativity, rule and forumla required');
        }

        return true;
    }

    /**
     * check structure value.
     *
     * @param array $value
     * @param bool  $affectedThePrice
     * @param bool  $asDefaultValue
     *
     * @return bool
     */
    protected function checkStructureValue(array $value, bool $affectedThePrice, bool $asDefaultValue = false)
    {
        if (!$asDefaultValue and count($value) < 2) {
            throw new InvalidArgumentException('structure value count minimal 2');
        }

        // should be array of
        // ['label' => 'string', 'value' => 'integer']
        if ($affectedThePrice) {
            foreach ($value as $key => $val) {
                if (!is_array($val)) {
                    throw new InvalidArgumentException(($asDefaultValue ? 'default value ' : 'value ').$key.' should be array');
                }

                if (!isset($val['label']) or !is_string($val['label'])) {
                    throw new InvalidArgumentException(($asDefaultValue ? 'default value ' : 'value ').$key.' should contain key label and string');
                }

                if (!isset($val['value']) or !is_int($val['value'])) {
                    throw new InvalidArgumentException(($asDefaultValue ? 'default value ' : 'value ').$key.' should contain key value and integer');
                }
            }
        }

        // should be array of string
        else {
            foreach ($value as $key => $val) {
                if (!is_string($val)) {
                    throw new InvalidArgumentException(($asDefaultValue ? 'default value ' : 'value ').$key.' should be string');
                }
            }
        }
    }

    /**
     * check structure default value.
     *
     * @param mixed $default
     * @param bool  $required
     * @param bool  $multiple
     * @param bool  $affectedThePrice
     *
     * @return bool
     */
    protected function checkStructureDefaultValue($default, bool $required, bool $multiple, bool $affectedThePrice)
    {
        if ($required and empty($default)) {
            throw new InvalidArgumentException('default value should be defined');
        }

        if ($multiple and !is_array($default)) {
            throw new InvalidArgumentException('default value should be array when multiple');
        }

        if ($multiple) {
            $this->checkStructureValue($default, $affectedThePrice, true);
        } elseif (!empty($default)) {
            $this->checkStructureValue([$default], $affectedThePrice, true);
        }

        return true;
    }

    /**
     * check is default value in selection.
     *
     * @param mixed $default
     * @param array $selection
     * @param bool  $multiple
     *
     * @return bool
     */
    protected function checkisDefaultValueInSelection($default, array $selection, bool $multiple)
    {
        if ($multiple) {
            // default value has been checked as array
            foreach ($default as $val) {
                $this->checkisDefaultValueInSelection($val, $selection, false);
            }
        } elseif (!in_array($default, $selection)) {
            throw new InvalidArgumentException('default value is not in selection');
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
    public function isValidValue($value)
    {
        parent::isValidValue($value);

        $structure = $this->serviceOptionConfiguration->structure;

        if ($structure['affected_the_price']) {
            if (!is_array($value)) {
                throw new InvalidArgumentException('value is not array');
            }

            if ($structure['multiple']) {
                foreach ($value as $val) {
                    if (!is_array($val)) {
                        throw new InvalidArgumentException('value is not array of array');
                    }

                    if (!isset($val['label']) or !isset($val['value'])) {
                        throw new InvalidArgumentException('value or lable is not persent');
                    }

                    if (!in_array($val, $structure['value'])) {
                        throw new InvalidArgumentException('value is not in selection');
                    }
                }
            } else {
                if (!isset($value['label']) or !isset($value['value'])) {
                    throw new InvalidArgumentException('value or lable is not persent');
                }

                if (!in_array($value, $structure['value'])) {
                    throw new InvalidArgumentException('value is not in selection');
                }
            }
        } else {
            if ($structure['multiple']) {
                if (!is_array($value)) {
                    throw new InvalidArgumentException('value is not array');
                }
                foreach ($value as $val) {
                    if (!is_string($val)) {
                        throw new InvalidArgumentException('value is not array of string');
                    }

                    if (!in_array($val, $structure['value'])) {
                        throw new InvalidArgumentException('value is not in selection');
                    }
                }
            } else {
                if ($structure['required'] and !is_string($value)) {
                    throw new InvalidArgumentException('value is not string');
                }

                if ($structure['required'] and !in_array($value, $structure['value'])) {
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

        $initialUnitPrice = $unitPrice;
        $initialUnitTotal = $unitTotal;

        if ($structure['affected_the_price']) {
            if ($structure['multiple']) {
                foreach ($value as $val) {
                    $this->calculateSelected($val['value'], $structure, $quantity, $unitPrice, $unitTotal);
                }
            } else {
                $this->calculateSelected($value['value'], $structure, $quantity, $unitPrice, $unitTotal);
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
            'initial' => [
                'unit_price' => $initialUnitPrice,
                'unit_total' => $initialUnitTotal,
            ],
        ];
    }

    /**
     * calculate selected.
     *
     * @param mixed $value
     * @param array $structure
     * @param mixed $value
     * @param int   $quantity
     * @param int   $unitPrice
     * @param int   $unitTotal
     */
    protected function calculateSelected($value, array $structure, int $quantity, int &$unitPrice, int &$unitTotal)
    {
        if (!method_exists($this, $methode = $structure['formula'])) {
            throw new InvalidArgumentException('Unknwon formula '.$structure['formula']);
        }

        $relativeValue = $this->getRelativeValue($unitPrice, $unitTotal);

        if ('percentage' === $structure['rule']) {
            $value = ceil(($relativeValue * $value) / 100);
        }

        $calculated = $this->$methode($value, $relativeValue);

        if ('unit_price' === $structure['relativity']) {
            $unitPrice = $calculated;
            $unitTotal = $quantity * $calculated;
        } else {
            $unitTotal = $calculated;
        }
    }

    /**
     * multiplication.
     *
     * @param int $value
     * @param int $relativeValue
     *
     * @return int
     */
    public function multiplication(int $value, int $relativeValue)
    {
        return $relativeValue *= $value;
    }

    /**
     * division.
     *
     * @param int $value
     * @param int $relativeValue
     *
     * @return int
     */
    public function division(int $value, int $relativeValue)
    {
        return $relativeValue /= $value;
    }

    /**
     * addition.
     *
     * @param int $value
     * @param int $relativeValue
     *
     * @return int
     */
    public function addition(int $value, int $relativeValue)
    {
        return $relativeValue += $value;
    }

    /**
     * reduction.
     *
     * @param int $value
     * @param int $relativeValue
     *
     * @return int
     */
    public function reduction(int $value, int $relativeValue)
    {
        return $relativeValue -= $value;
    }
}
