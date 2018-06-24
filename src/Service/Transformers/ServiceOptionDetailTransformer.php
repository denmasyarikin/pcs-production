<?php

namespace Denmasyarikin\Production\Service\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitListDetailTransformer;
use Denmasyarikin\Production\Service\Factories\ServicePriceCalculator;

class ServiceOptionDetailTransformer extends Detail
{
    /**
     * get data.
     *
     * @param Model $model
     *
     * @return array
     */
    protected function getData(Model $model)
    {
        $calculator = new ServicePriceCalculator($model);

        return [
            'id' => $model->id,
            'name' => $model->name,
            'service_id' => $model->service_id,
            'service' => (new ServiceDetailTransformer($model->service))->toArray(),
            'unit_id' => $model->unit_id,
            'unit' => (new UnitListDetailTransformer($model->unit))->toArray(),
            'min_order' => $model->min_order,
            'order_multiples' => $model->order_multiples,
            'base_price' => $calculator->getBasePrice() ? $calculator->getBasePrice()->price : null,
            'prices' => (new ServicePriceListTransformer($calculator->getAllPrices()))->toArray(),
            'configurations' => (new ServiceOptionConfigurationListTransformer($model->serviceOptionConfigurations))->toArray(),
            'enabled' => $model->enabled,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
