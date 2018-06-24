<?php

namespace Denmasyarikin\Production\Service\Requests;

use App\Http\Requests\FormRequest;
use Denmasyarikin\Production\Service\ServiceOption;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailOptionRequest extends FormRequest
{
    /**
     * serviceOption.
     *
     * @var ServiceOption
     */
    public $serviceOption;

    /**
     * get serviceOption.
     *
     * @return ServiceOption
     */
    public function getServiceOption(): ?ServiceOption
    {
        if ($this->serviceOption) {
            return $this->serviceOption;
        }

        $id = (int) $this->route('id');

        if ($this->serviceOption = ServiceOption::find($id)) {
            return $this->serviceOption;
        }

        throw new NotFoundHttpException('Service Option Not Found');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
