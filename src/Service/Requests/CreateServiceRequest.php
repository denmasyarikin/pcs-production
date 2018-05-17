<?php

namespace Denmasyarikin\Production\Service\Requests;

use App\Http\Requests\FormRequest;

class CreateServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'description' => 'nullable',
        ];
    }
}
