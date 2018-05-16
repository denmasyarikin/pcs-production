<?php

namespace Denmasyarikin\Production\Service\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

class ServiceDetailTransformer extends Detail
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
            'description' => $model->description,
            'status' => $model->status,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
