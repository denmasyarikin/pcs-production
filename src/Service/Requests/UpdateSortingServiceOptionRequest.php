<?php 

namespace Denmasyarikin\Production\Service\Requests;

use App\Http\Requests\FormRequest;

class UpdateSortingServiceOptionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.*.id' => 'required|exists:production_service_options,id',
            'data.*.sort' => 'required|integer'
        ];
    }
}