<?php

namespace Denmasyarikin\Production\Service;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePrice extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_service_prices';

    /**
     * Get the serviceType record associated with the ServicePrice.
     */
    public function serviceType()
    {
    	return $this->belongsTo(ServiceType::class);
    }
}
