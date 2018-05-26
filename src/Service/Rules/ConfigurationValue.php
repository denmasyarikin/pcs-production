<?php

namespace Denmasyarikin\Production\Service\Rules;

use Illuminate\Contracts\Validation\Rule;
use Denmasyarikin\Production\Service\ServiceOptionConfiguration;
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
     * service option configuration.
     *
     * @var ServiceOptionConfiguration
     */
    protected $serviceOptionConfiguration;

    /**
     * Create a new ConfigurationValue instance.
     *
     * @param ServiceOptionConfiguration $serviceOptionConfiguration
     */
    public function __construct(ServiceOptionConfiguration $serviceOptionConfiguration)
    {
        $this->serviceOptionConfiguration = $serviceOptionConfiguration;
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

        if (!$manager->isConfigurationExists($this->serviceOptionConfiguration->type)) {
            $this->message = 'The :attribute can not be processed, no configuration found';

            return false;
        }

        $factory = $manager->getConfigurationInstance($this->serviceOptionConfiguration->type);
        $factory->setServiceOptionConfiguration($this->serviceOptionConfiguration);

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
