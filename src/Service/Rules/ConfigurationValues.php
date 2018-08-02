<?php

namespace Denmasyarikin\Production\Service\Rules;

use Denmasyarikin\Production\Service\ServiceOption;

class ConfigurationValues extends ConfigurationValue
{
    /**
     * service option.
     *
     * @var ServiceOption
     */
    protected $serviceOption;

    /**
     * Create a new ConfigurationRules instance.
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
        $configurations = $this->serviceOption->serviceOptionConfigurations;

        if (0 === $configurations->count()) {
            return true;
        }

        if ($configurations->count() > 1 and !is_array($value)) {
            $this->message = 'The :attribute should be an array where configurations is multiple';

            return false;
        }

        foreach ($configurations as $config) {
            $id = 0;
            $currentValue = $value;

            if (!array_key_exists($config->id, (array) $value)) {
                $this->message = 'The :attribute not contain key service option configuration id '.$config->id;

                return false;
            }

            $currentValue = $value[$config->id];

            $this->serviceOptionConfiguration = $config;

            if (!parent::passes($attribute, $currentValue)) {
                return false;
            }
        }

        return true;
    }
}
