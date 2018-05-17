<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

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
}
