<?php

namespace Denmasyarikin\Production\Service;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

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
}
