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
     *
     * @return array
     */
    public function calculatePrice(int $quantity, $value, int $chanelId = null)
    {
        $price = $this->getPrice($chanelId);
        $manager = new ConfigurationManager();

        if (is_null($price)) {
            throw new InvalidArgumentException('Price not found');
        }

        $calculation = $this->generateCalculation($quantity, $price);

        foreach ($this->priceable->serviceTypeConfigurations as $configuration) {
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
     * @return data type
     */
    protected function generateCalculation(int $quantity, ServicePrice $servicePrice)
    {
        return new Calculation($quantity, $servicePrice->price);
    }
}