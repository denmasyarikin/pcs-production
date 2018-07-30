<?php

namespace Denmasyarikin\Production\Service\Factories;

use App\Manager\ChanelPriceCalculator;
use Denmasyarikin\Production\Service\ServicePrice;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class ServicePriceCalculator extends ChanelPriceCalculator
{
    /**
     * calculate price.
     *
     * @param int   $quantity
     * @param mixed $value
     * @param int   $chanelId
     * @param int   $defaultPrice
     *
     * @return array
     */
    public function calculatePrice(int $quantity, $value, int $chanelId = null, int $defaultPrice = null)
    {
        $price = $this->getPrice($chanelId, true, $defaultPrice);

        $manager = new ConfigurationManager();

        if (is_null($price)) {
            throw new InvalidArgumentException('Price not found');
        }

        $calculation = $this->generateCalculation($quantity, $price);

        foreach ($this->priceable->serviceOptionConfigurations as $configuration) {
            $val = $manager->getValueFromRequest($this->priceable, $configuration, $value);
            $calculation->applyConfiguration($configuration, $val);
        }

        return $calculation;
    }

    /**
     * generate calculation.
     *
     * @param int          $quantity
     * @param ServicePrice $servicePrice
     *
     * @return Calculation
     */
    protected function generateCalculation(int $quantity, ServicePrice $servicePrice)
    {
        return new Calculation($quantity, $servicePrice->price);
    }
}
