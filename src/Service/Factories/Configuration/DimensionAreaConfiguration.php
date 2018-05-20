<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

class DimensionAreaConfiguration extends DimensionConfiguration
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'area';

    /**
     * dimension
     *
     * @var array
     */
    protected $dimension = ['width', 'length'];
}
