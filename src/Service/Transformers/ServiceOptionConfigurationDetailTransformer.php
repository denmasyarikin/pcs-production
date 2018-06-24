<?php

namespace Denmasyarikin\Production\Service\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

class ServiceOptionConfigurationDetailTransformer extends Detail
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
        $serviceOption = $model->serviceOption;

        return [
            'id' => $model->id,
            'name' => $model->name,
            'sequence' => $model->sequence,
            'type' => $model->type,
            'service_option_id' => $model->service_option_id,
            'service_type' => [
                'id' => $serviceOption->id,
                'service_id' => $serviceOption->service_id,
                'name' => $serviceOption->name,
                'unit_id' => $serviceOption->unit_id,
                'min_order' => $serviceOption->min_order,
                'order_multiples' => $serviceOption->order_multiples,
            ],
            'structure' => $model->structure,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
