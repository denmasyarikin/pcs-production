<?php

namespace Denmasyarikin\Production\Service\Requests;

class UpdateServicePriceRequest extends DetailServicePriceRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['price' => 'required|numeric'];
    }
}
