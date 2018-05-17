<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

class MultiplicationVolumeConfiguration extends Configuration implements ConfigurationInterface
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'multiplication_volume';

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
        'min_height' => 'integer',
        'max_height' => 'integer',
        'default_width' => 'integer',
        'default_length' => 'integer',
        'default_height' => 'integer',
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

        if (!isset($value['width']) OR !is_int($value['width'])) {
            throw new InvalidArgumentException('Width Not present or not integer');
        }

        if (!isset($value['length']) OR !is_int($value['length'])) {
            throw new InvalidArgumentException('Length Not present or not integer');            
        }

        if (!isset($value['height']) OR !is_int($value['height'])) {
            throw new InvalidArgumentException('Height Not present or not integer');            
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

        if ($value['height'] < $configuration['min_height']) {
            throw new InvalidArgumentException('Height Less then ' . $configuration['min_height']);
        }

        if ($value['height'] > $configuration['min_height']) {
            throw new InvalidArgumentException('Height More then ' . $configuration['max_height']);
        }

        return true;
    }
}
