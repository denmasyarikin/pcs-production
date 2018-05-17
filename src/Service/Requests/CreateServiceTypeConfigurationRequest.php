<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\Rules\ConfigurationType;
use Denmasyarikin\Production\Service\Rules\ConfigurationRules;

class CreateServiceTypeConfigurationRequest extends DetailServiceTypeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:30',
            'type' => ['required', new ConfigurationType],
            'configuration' => ['required', 'array', new ConfigurationRules($this->type)],
            'required' => 'required|boolean'
        ];
    }
}
