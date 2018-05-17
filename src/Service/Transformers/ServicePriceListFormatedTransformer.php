<?php

namespace Denmasyarikin\Production\Service\Transformers;

use Modules\Chanel\Chanel;
use App\Manager\Facades\Money;
use App\Http\Transformers\Collection;
use Denmasyarikin\Production\Service\ServicePrice;
use Illuminate\Database\Eloquent\Collection as IlluminateCollection;

class ServicePriceListFormatedTransformer extends Collection
{
    /**
     * get data.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [];

        $basePrice = $this->data->whereStrict('chanel_id', null)->whereStrict('current', 1)->first();

        if ($basePrice) {
            $data[] = [
                'type' => 'base',
                'data' => $this->generateSingleData($basePrice),
            ];

            foreach (Chanel::whereStatus('active')->get() as $chanel) {
                $data[] = [
                    'type' => $chanel->name,
                    'data' => $this->getChanelPrice($chanel, $this->data, $basePrice),
                ];
            }
        }

        return $data;
    }

    /**
     * get chanel price.
     *
     * @param Chanel       $chanel
     * @param Collection   $prices
     * @param ServicePrice $basePrice
     *
     * @return array
     */
    protected function getChanelPrice(Chanel $chanel, IlluminateCollection $prices, ServicePrice $basePrice)
    {
        $chanelPrice = $prices->whereStrict('chanel_id', $chanel->id)->whereStrict('current', 1)->first();

        if ($chanelPrice) {
            return $this->generateSingleData($chanelPrice);
        }

        return $this->generateChanelPrice($chanel, $basePrice);
    }

    /**
     * generate single data.
     *
     * @param ServicePrice $price
     *
     * @return array
     */
    protected function generateSingleData(ServicePrice $price)
    {
        return [
            'id' => $price->id,
            'chanel_id' => $price->chanel_id,
            'price' => (int) $price->price,
        ];
    }

    /**
     * generate chanel price.
     *
     * @param Chanel       $chanel
     * @param ServicePrice $basePrice
     *
     * @return array
     */
    protected function generateChanelPrice(Chanel $chanel, ServicePrice $basePrice)
    {
        return [
            'id' => null,
            'chanel_id' => $chanel->id,
            'price' => (int) $basePrice->price + Money::round($basePrice->price * $chanel->markup / 100),
        ];
    }
}
