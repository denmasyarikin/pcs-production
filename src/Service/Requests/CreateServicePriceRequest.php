<?php

namespace Denmasyarikin\Production\Service\Requests;

class CreateServicePriceRequest extends DetailServiceTypeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'chanel_id' => 'nullable|exists:core_chanels,id',
            'price' => 'required|numeric',
        ];
    }
}
