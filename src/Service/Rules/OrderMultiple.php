<?php

namespace Denmasyarikin\Production\Service\Rules;

use Illuminate\Contracts\Validation\Rule;
use Denmasyarikin\Production\Service\ServiceOption;

class OrderMultiple implements Rule
{
    /**
     * service option.
     *
     * @var ServiceOption
     */
    protected $serviceOption;

    /**
     * Create a new OrderMultiple instance.
     *
     * @param ServiceOption $serviceOption
     */
    public function __construct(ServiceOption $serviceOption)
    {
        $this->serviceOption = $serviceOption;
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
        return 0 === $value % $this->serviceOption->order_multiples;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not multiple of '.$this->serviceOption->order_multiples;
    }
}
