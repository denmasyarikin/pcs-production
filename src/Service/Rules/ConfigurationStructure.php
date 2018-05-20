<?php

namespace Denmasyarikin\Production\Service\Rules;

use Illuminate\Contracts\Validation\Rule;
use Denmasyarikin\Production\Service\Factories\ConfigurationManager;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ConfigurationStructure implements Rule
{
    /**
     * message.
     *
     * @var string
     */
    protected $message = 'The :attribute is not valid';

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

        if (!$manager->isConfigurationExists($this->type)) {
            $this->message = 'The :attribute can not be processed, no configuration found';
            return false;
        }

        $factory = $manager->getConfigurationInstance($this->type);

        try {
            return $factory->isValidStructure($value);
        } catch (InvalidArgumentException $e) {
            $this->message = 'The :attribute is invalid  : '.$e->getMessage();
            return false;
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
        return $this->message;
    }
}
