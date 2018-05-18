<?php

namespace Denmasyarikin\Production\Service;

use App\Model;
use App\Manager\Contracts\Priceable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model implements Priceable
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_service_types';

    /**
     * Get the service record associated with the ServicePrice.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the servicePrices record associated with the ServiceType.
     */
    public function servicePrices()
    {
        return $this->hasMany(ServicePrice::class);
    }

    /**
     * get prices
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getPrices()
    {
        return $this->servicePrices();
    }

    /**
     * Get the serviceConfigurations record associated with the ServiceType.
     */
    public function serviceTypeConfigurations()
    {
        return $this->hasMany(ServiceTypeConfiguration::class);
    }
}
