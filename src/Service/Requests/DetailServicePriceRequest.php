<?php

namespace Denmasyarikin\Production\Service\Requests;

use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\ServicePrice;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailServicePriceRequest extends DetailServiceOptionRequest
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
    public function getServicePrice(ServiceOption $serviceOption = null): ?ServicePrice
    {
        if ($this->servicePrice) {
            return $this->servicePrice;
        }

        $serviceOption = null === $serviceOption ? $this->getServiceOption() : $serviceOption;
        $id = (int) $this->route('price_id');

        if ($this->servicePrice = $serviceOption->servicePrices()->find($id)) {
            return $this->servicePrice;
        }

        throw new NotFoundHttpException('Service Price Not Found');
    }
}
