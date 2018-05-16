<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServicePrice;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailServicePriceRequest extends DetailServiceTypeRequest
{
    /**
     * servicePrice.
     *
     * @var ServicePrice
     */
    public $servicePrice;

    /**
     * get servicePrice.
     *
     * @return ServicePrice
     */
    public function getServicePrice(ServiceType $serviceType = null): ?ServicePrice
    {
        if ($this->servicePrice) {
            return $this->servicePrice;
        }

        $serviceType = null === $serviceType ? $this->getServiceType() : $serviceType;
        $id = (int) $this->route('price_id');

        if ($this->servicePrice = $serviceType->servicePrices()->find($id)) {
            return $this->servicePrice;
        }

        throw new NotFoundHttpException('Service Price Not Found');
    }
}
