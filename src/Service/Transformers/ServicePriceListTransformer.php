<?php

namespace Denmasyarikin\Production\Service\Transformers;

use App\Http\Transformers\Collection;
use Illuminate\Database\Eloquent\Model;

class ServicePriceListTransformer extends Collection
{
    /**
     * get data.
     *
     * @return array
     */
    protected function getData(Model $model)
    {
        return (new ServicePriceDetailTransformer($model))->toArray();
    }
}
