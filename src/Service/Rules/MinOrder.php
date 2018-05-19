<?php

namespace Denmasyarikin\Production\Service\Rules;

use Illuminate\Contracts\Validation\Rule;
use Denmasyarikin\Production\Service\ServiceType;

class MinOrder implements Rule
{
    /**
     * service type.
     *
     * @var ServiceType
     */
    protected $serviceType;

    /**
     * Create a new MinOrder instance.
     *
     * @param ServiceType $serviceType
     */
    public function __construct(ServiceType $serviceType)
    {
        $this->serviceType = $serviceType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value >= $this->serviceType->min_order;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute less then minimal order '.$this->serviceType->min_order;
    }
}
