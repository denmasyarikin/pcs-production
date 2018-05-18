<?php

namespace Denmasyarikin\Production\Service\Rules;

use Illuminate\Contracts\Validation\Rule;
use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;
use Denmasyarikin\Production\Service\Factories\ConfigurationManager;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ConfigurationValue implements Rule
{
    /**
     * message
     *
     * @var string
     */
    protected $message = 'The :attribute is not valid';

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
        
        if ($configurations->count() === 0) {
            return true;
        }

        if ($configurations->count() > 1 AND !is_array($value)) {
            $this->message = 'The :attribute should be an array where configurations is multiple';
            return false;
        }

        foreach ($configurations as $config) {
            $id = 0;
            $currentValue = $value;

            if ($configurations->count() > 1) {
                if (!array_key_exists($config->id, $value)) {
                    $this->message = 'The :attribute not contain key service type configuration id ' . $config->id;
                    return false;
                }

                $currentValue = $value[$config->id];
            }

            if (!$this->validateConfigurationValue($config, $currentValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * validate each configuration value
     *
     * @param ServiceTypeConfiguration $typeConfiguration
     * @param mixed $value
     *
     * @return bool
     */
    public function validateConfigurationValue(ServiceTypeConfiguration $typeConfiguration, $value) {
        $manager = new ConfigurationManager();
        
        if (!$manager->isConfigurationExists($typeConfiguration->type)) {
            $this->message = 'The :attribute can not be processed, no configuration found';
            return false;
        }

        $factory = $manager->getConfigurationInstance($typeConfiguration->type);
        $factory->setServiceTypeConfiguration($typeConfiguration);

        try {
            return $factory->isValidValue($value);
        } catch (InvalidArgumentException $e) {
            $this->message = 'The :attribute ' . $typeConfiguration->id .' invalid  : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
