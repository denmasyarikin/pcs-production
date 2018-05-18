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

        $configuration = $this->serviceTypeConfiguration;

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
        dd($value);
    }
}
