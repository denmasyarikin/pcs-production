<?php

namespace Denmasyarikin\Production\Service\Rules;

use Illuminate\Contracts\Validation\Rule;
use Denmasyarikin\Production\Service\Factories\ConfigurationManager;

class ConfigurationRules implements Rule
{
    /**
     * type.
     *
     * @var string
     */
    protected $type;

    /**
     * Create a new ConfigurationRules instance.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
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

        if (!is_array($value)) {
            return false;
        }

        $configuration = $manager->getConfigurationInstance($this->type);

        if (!is_null($configuration)) {
            return $configuration->isValidStructure($value);
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not valid';
    }
}
