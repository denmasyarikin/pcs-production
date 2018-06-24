<?php

namespace Denmasyarikin\Production\Service\Factories\Configuration;

class DimensionVolumeConfiguration extends DimensionConfiguration
{
    /**
     * configuration type.
     *
     * @var string
     */
    protected $type = 'volume';

    /**
     * dimension.
     *
     * @var array
     */
    protected $dimension = ['width', 'length', 'height'];
}
