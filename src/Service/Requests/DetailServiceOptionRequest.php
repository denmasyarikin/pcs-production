<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\Service;
use Denmasyarikin\Production\Service\ServiceOption;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailServiceOptionRequest extends DetailServiceRequest
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
    public function getServiceOption(Service $service = null): ?ServiceOption
    {
        if ($this->serviceOption) {
            return $this->serviceOption;
        }

        $service = null === $service ? $this->getService() : $service;
        $id = (int) $this->route('option_id');

        if ($this->serviceOption = $service->serviceOptions()->find($id)) {
            return $this->serviceOption;
        }

        throw new NotFoundHttpException('Service Option Not Found');
    }
}
