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
        return [
            'id' => $model->id,
            'name' => $model->name,
            'type' => $model->type,
            'service_type_id' => $model->service_type_id,
            'service_type' => (new ServiceTypeDetailTransformer($model->serviceType))->toArray(),
            'configuration' => $model->configuration,
            'required' => (bool) $model->required,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
