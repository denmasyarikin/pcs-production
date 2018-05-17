<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailServiceTypeConfigurationRequest extends DetailServiceTypeRequest
{
    /**
     * serviceTypeConfiguration.
     *
     * @var ServiceType
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
}
