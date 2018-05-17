<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class MultiplicationAreaConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'multiplication_area';

    /**
     * structure.
     *
     * @var array
     */
    protected $structure = [
        'relativity' => ['unit_price', 'unit_total'],
        'min_width' => 'integer',
        'max_width' => 'integer',
        'min_length' => 'integer',
        'max_length' => 'integer',
        'default_width' => 'integer',
        'default_length' => 'integer',
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

        if (!is_array($value)) {
            throw new InvalidArgumentException('Not array');            
        }

        if (!isset($value['width'])) {
            throw new InvalidArgumentException('Width Not present');            
        }

        if (!isset($value['length'])) {
            throw new InvalidArgumentException('Length Not present');            
        }

        if ($value['width'] < $configuration['min_width']) {
            throw new InvalidArgumentException('Width Less then ' . $configuration['min_width']);
        }

        if ($value['width'] > $configuration['min_width']) {
            throw new InvalidArgumentException('Width More then ' . $configuration['max_width']);
        }

        if ($value['length'] < $configuration['min_length']) {
            throw new InvalidArgumentException('Length Less then ' . $configuration['min_length']);
        }

        if ($value['length'] > $configuration['min_length']) {
            throw new InvalidArgumentException('Length More then ' . $configuration['max_length']);
        }

        return true;
    }
}
