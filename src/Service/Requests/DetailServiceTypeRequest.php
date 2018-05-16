<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\Service;
use Denmasyarikin\Production\Service\ServiceType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailServiceTypeRequest extends DetailServiceRequest
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
    public function getServiceType(Service $service = null): ?ServiceType
    {
        if ($this->serviceType) {
            return $this->serviceType;
        }

        $service = null === $service ? $this->getService() : $service;
        $id = (int) $this->route('type_id');

        if ($this->serviceType = $service->serviceTypes()->find($id)) {
            return $this->serviceType;
        }

        throw new NotFoundHttpException('Service Type Not Found');
    }
}
