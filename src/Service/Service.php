<?php

namespace Denmasyarikin\Production\Service;

use App\Model;
use Modules\Workspace\WorkspaceRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes, WorkspaceRelation;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_services';

    /**
     * Get the serviceTypes record associated with the Service.
     */
    public function serviceTypes()
    {
        return $this->hasMany(ServiceType::class);
    }

    /**
     * Get the workspaces record associated with the Good.
     */
    public function workspaces()
    {
        return $this->belongsToMany('Modules\Workspace\Workspace', 'production_service_workspaces')->whereStatus('active')->withTimestamps();
    }
}
