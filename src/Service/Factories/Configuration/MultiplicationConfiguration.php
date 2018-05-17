<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class MultiplicationConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'multiplication';

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = [
        'relativity' => ['unit_price', 'unit_total'],
        'min' => 'integer',
        'max' => 'integer',
        'default' => 'integer',
    ];

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
        
        if (!is_int($value)) {
            throw new InvalidArgumentException('Not an integer');
        }

        if ($value < $configuration['min']) {
            throw new InvalidArgumentException('Less then ' . $configuration['min']);
        }

        if ($value > $configuration['max']) {
            throw new InvalidArgumentException('More then ' . $configuration['max']);
        }

        return true;
    }
}
