<?php

namespace Denmasyarikin\Production\Service\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Chanel\Transformers\ChanelDetailTransformer;

class ServicePriceDetailTransformer extends Detail
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
            'chanel_id' => $model->chanel_id,
            'chanel' => (new ChanelDetailTransformer($model->chanel, ['id', 'name', 'type', 'markup', 'required_down_payment', 'due_date_day_count']))->toArray(),
            'price' => (float) $model->price,
            'current' => (bool) $model->current,
            'previous_id' => $model->previous_id,
            'change_type' => $model->change_type,
            'difference' => $model->difference,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
