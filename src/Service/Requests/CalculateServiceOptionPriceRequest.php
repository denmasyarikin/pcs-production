<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\Service;
use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\Rules\MinOrder;
use Denmasyarikin\Production\Service\Rules\OrderMultiple;
use Denmasyarikin\Production\Service\Rules\ConfigurationValues;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CalculateServiceOptionPriceRequest extends DetailServiceOptionRequest
{
    /**
     * get serviceOption.
     *
     * @return ServiceOption
     */
    public function getServiceOption(Service $service = null): ?ServiceOption
    {
        $serviceOption = parent::getServiceOption($service);

        if ($serviceOption->servicePrices()->count() > 0) {
            return $serviceOption;
        }

        throw new NotFoundHttpException('Service Option has no price');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $serviceOption = $this->getServiceOption();

        return [
            'quantity' => ['required', 'numeric', new MinOrder($serviceOption), new OrderMultiple($serviceOption)],
            'chanel_id' => 'nullable|exists:core_chanels,id',
            'unit_price' => 'nullable|numeric',
            'value' => [new ConfigurationValues($serviceOption)],
        ];
    }
}
