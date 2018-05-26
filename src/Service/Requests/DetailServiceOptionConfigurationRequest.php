<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\ServiceOptionConfiguration;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailServiceOptionConfigurationRequest extends DetailServiceOptionRequest
{
    /**
     * serviceOptionConfiguration.
     *
     * @var ServiceOption
     */
    public $serviceOptionConfiguration;

    /**
     * get serviceOptionConfiguration.
     *
     * @return ServiceOption
     */
    public function getServiceOptionConfiguration(ServiceOption $serviceOption = null): ?ServiceOptionConfiguration
    {
        if ($this->serviceOptionConfiguration) {
            return $this->serviceOptionConfiguration;
        }

        $serviceOption = null === $serviceOption ? $this->getServiceOption() : $serviceOption;
        $id = (int) $this->route('configuration_id');

        if ($this->serviceOptionConfiguration = $serviceOption->serviceOptionConfigurations()->find($id)) {
            return $this->serviceOptionConfiguration;
        }

        throw new NotFoundHttpException('Service Option Configuration Not Found');
    }
}
