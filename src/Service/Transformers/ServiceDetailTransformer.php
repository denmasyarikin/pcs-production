<?php

namespace Denmasyarikin\Production\Service\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Workspace\Transformers\WorkspaceListTransformer;

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
            'service_option_count' => $model->serviceOptions()->count(),
            'workspace_ids' => $model->workspaces->pluck('id'),
            'workspaces' => (new WorkspaceListTransformer($model->workspaces))->toArray(),
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
