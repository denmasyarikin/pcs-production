<?php

namespace Denmasyarikin\Production\Service\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitListDetailTransformer;

class ServiceTypeDetailTransformer extends Detail
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
        return [
            'id' => $model->id,
            'name' => $model->name,
            'service_id' => $model->service_id,
            'service' => (new ServiceDetailTransformer($model->service))->toArray(),
            'unit_id' => $model->unit_id,
            'unit' => (new UnitListDetailTransformer($model->unit))->toArray(),
            'min_order' => $model->min_order,
            'order_multiples' => $model->order_multiples,
            'prices' => (new ServicePriceListFormatedTransformer($model->servicePrices))->toArray(),
            'enabled' => $model->enabled,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}