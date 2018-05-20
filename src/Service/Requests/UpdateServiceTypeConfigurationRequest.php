<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\Rules\ConfigurationType;
use Denmasyarikin\Production\Service\Rules\ConfigurationStructure;

class UpdateServiceTypeConfigurationRequest extends DetailServiceTypeConfigurationRequest
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
            'type' => ['required', new ConfigurationType()],
            'structure' => ['required', 'array', new ConfigurationStructure($this->type)]
       ];
    }
}
