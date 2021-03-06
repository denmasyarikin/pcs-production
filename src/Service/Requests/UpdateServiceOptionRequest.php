<?php

namespace Denmasyarikin\Production\Service\Requests;

class UpdateServiceOptionRequest extends DetailServiceOptionRequest
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
            'enabled' => 'required|boolean',
            'free_input' => 'required|boolean'
       ];
    }
}
