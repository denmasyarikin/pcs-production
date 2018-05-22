<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\Rules\MinOrder;
use Denmasyarikin\Production\Service\Rules\OrderMultiple;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;
use Denmasyarikin\Production\Service\Rules\ConfigurationValue;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CalculateServiceTypeConfigurationPriceRequest extends CalculateServiceTypePriceRequest
{
    /**
     * Service Type Configuration.
     *
     * @var ServiceTypeConfiguration
     */
    public $serviceTypeConfiguration;

    /**
     * get serviceTypeConfiguration.
     *
     * @return ServiceType
     */
    public function getServiceTypeConfiguration(ServiceType $serviceType = null): ?ServiceTypeConfiguration
    {
        if ($this->serviceTypeConfiguration) {
            return $this->serviceTypeConfiguration;
        }

        $serviceType = null === $serviceType ? $this->getServiceType() : $serviceType;
        $id = (int) $this->route('configuration_id');

        if ($this->serviceTypeConfiguration = $serviceType->serviceTypeConfigurations()->find($id)) {
            return $this->serviceTypeConfiguration;
        }

        throw new NotFoundHttpException('Service Type Configuration Not Found');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $serviceTypeConfiguration = $this->getServiceTypeConfiguration();

        return [
            'quantity' => ['required', 'numeric', new MinOrder($serviceTypeConfiguration->serviceType), new OrderMultiple($serviceTypeConfiguration->serviceType)],
            'chanel_id' => 'nullable|exists:core_chanels,id',
            // if not need value, it values will be ignored
            'value' => ['required', new ConfigurationValue($serviceTypeConfiguration)],
        ];
    }
}
