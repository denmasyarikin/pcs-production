<?php

namespace Denmasyarikin\Production\Service\Transformers;

use App\Http\Transformers\Collection;
use Illuminate\Database\Eloquent\Model;

class ServiceTypeListTransformer extends Collection
{
    /**
     * get data.
     *
     * @return array
     */
    protected function getData(Model $model)
    {
        return (new ServiceTypeDetailTransformer($model))->toArray();
    }
}
