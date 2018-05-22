<?php

namespace Denmasyarikin\Production\Service\Rules;

use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;
use Denmasyarikin\Production\Service\Factories\ConfigurationManager;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ConfigurationValues implements ConfigurationValue
{
    /**
     * service type.
     *
     * @var ServiceType
     */
    protected $serviceType;

    /**
     * Create a new ConfigurationRules instance.
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
        $configurations = $this->serviceType->serviceTypeConfigurations;

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

            if ($configurations->count() > 1) {
                if (!array_key_exists($config->id, $value)) {
                    $this->message = 'The :attribute not contain key service type configuration id '.$config->id;

                    return false;
                }

                $currentValue = $value[$config->id];
            }

            $this->serviceTypeConfiguration = $config;

            if (!parent::passes($attribute, $currentValue)) {
                return false;
            }
        }

        return true;
    }
}
