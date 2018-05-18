<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\Service;
use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\Rules\MinOrder;
use Denmasyarikin\Production\Service\Rules\OrderMultiple;
use Denmasyarikin\Production\Service\Rules\ConfigurationValue;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CalculateServiceTypePriceRequest extends DetailServiceTypeRequest
{
    /**
     * get serviceType.
     *
     * @return ServiceType
     */
    public function getServiceType(Service $service = null): ?ServiceType
    {
        $serviceType = parent::getServiceType($service);

        if ($serviceType->servicePrices()->count() > 0) {
            return $serviceType;
        }

        throw new NotFoundHttpException('Service Type has no price');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $serviceType = $this->getServiceType();

        return [
            'quantity' => ['required', 'numeric', new MinOrder($serviceType), new OrderMultiple($serviceType)],
            'chanel_id' => 'nullable|exists:core_chanels,id',
            // if not need value, it values will be ignored
            'value' => ['required', new ConfigurationValue($serviceType)],
        ];
    }
}
