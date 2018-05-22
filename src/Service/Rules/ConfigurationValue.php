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
     * message.
     *
     * @var string
     */
    protected $message = 'The :attribute is not valid';

    /**
     * service type configuration.
     *
     * @var ServiceTypeConfiguration
     */
    protected $serviceTypeConfiguration;

    /**
     * Create a new ConfigurationValue instance.
     *
     * @param ServiceTypeConfiguration $serviceTypeConfiguration
     */
    public function __construct(ServiceTypeConfiguration $serviceTypeConfiguration)
    {
        $this->serviceTypeConfiguration = $serviceTypeConfiguration;
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
        $manager = new ConfigurationManager();

        if (!$manager->isConfigurationExists($this->serviceTypeConfiguration->type)) {
            $this->message = 'The :attribute can not be processed, no configuration found';
            return false;
        }

        $factory = $manager->getConfigurationInstance($this->serviceTypeConfiguration->type);
        $factory->setServiceTypeConfiguration($this->serviceTypeConfiguration);

        try {
            return $factory->isValidValue($value);
        } catch (InvalidArgumentException $e) {
            $this->message = 'The :attribute invalid : '.$e->getMessage();
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
