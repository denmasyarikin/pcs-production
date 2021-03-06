<?php

namespace Denmasyarikin\Production\Service\Requests;

class CreateServiceOptionRequest extends DetailServiceRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|max:30',
            'unit_id' => 'required|exists:core_units,id',
            'min_order' => 'required|numeric',
            'order_multiples' => 'required|numeric',
            'free_input' => 'required|boolean',
        ];
    }
}
