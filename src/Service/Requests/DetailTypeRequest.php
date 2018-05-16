<?php

namespace Denmasyarikin\Production\Service\Requests;

use App\Http\Requests\FormRequest;
use Denmasyarikin\Production\Service\ServiceType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailTypeRequest extends FormRequest
{
    /**
     * serviceType.
     *
     * @var ServiceType
     */
    public $serviceType;

    /**
     * get serviceType.
     *
     * @return ServiceType
     */
    public function getServiceType(): ?ServiceType
    {
        if ($this->serviceType) {
            return $this->serviceType;
        }

        $id = (int) $this->route('id');

        if ($this->serviceType = ServiceType::find($id)) {
            return $this->serviceType;
        }

        throw new NotFoundHttpException('Service Type Not Found');
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
