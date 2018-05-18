<?php

namespace Denmasyarikin\Production\Service\Rules;

use Illuminate\Contracts\Validation\Rule;
use Denmasyarikin\Production\Service\ServiceType;

class OrderMultiple implements Rule
{
    /**
     * service type.
     *
     * @var ServiceType
     */
    protected $serviceType;

    /**
     * Create a new OrderMultiple instance.
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
        return $value % $this->serviceType->order_multiples === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not multiple of ' . $this->serviceType->order_multiples;
    }
}
