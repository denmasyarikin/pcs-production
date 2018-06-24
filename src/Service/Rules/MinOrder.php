<?php

namespace Denmasyarikin\Production\Service\Rules;

use Illuminate\Contracts\Validation\Rule;
use Denmasyarikin\Production\Service\ServiceOption;

class MinOrder implements Rule
{
    /**
     * service option.
     *
     * @var ServiceOption
     */
    protected $serviceOption;

    /**
     * Create a new MinOrder instance.
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
        return $value >= $this->serviceOption->min_order;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute less then minimal order '.$this->serviceOption->min_order;
    }
}
