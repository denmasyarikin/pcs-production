<?php

namespace Denmasyarikin\Production\Service\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

class ServiceTypeConfigurationDetailTransformer extends Detail
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
        $serviceType = $model->serviceType;

        return [
            'id' => $model->id,
            'name' => $model->name,
            'type' => $model->type,
            'service_type_id' => $model->service_type_id,
            'service_type' => [
                'id' => $serviceType->id,
                'service_id' => $serviceType->service_id,
                'name' => $serviceType->name,
                'unit_id' => $serviceType->unit_id,
                'min_order' => $serviceType->min_order,
                'order_multiples' => $serviceType->order_multiples
            ],
            'configuration' => $model->configuration,
            'required' => (bool) $model->required,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
