<?php

namespace Denmasyarikin\Production\Service;

use App\Model;
use App\Manager\Contracts\Priceable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOption extends Model implements Priceable
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_service_options';

    /**
     * Get the service record associated with the ServicePrice.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the servicePrices record associated with the ServiceOption.
     */
    public function servicePrices()
    {
        return $this->hasMany(ServicePrice::class);
    }

    /**
     * get prices.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getPrices()
    {
        return $this->servicePrices();
    }

    /**
     * Get the serviceOptionConfigurations record associated with the ServiceOption.
     */
    public function serviceOptionConfigurations()
    {
        return $this->hasMany(ServiceOptionConfiguration::class);
    }

    /**
     * Get the unit record associated with the GoodPrice.
     */
    public function unit()
    {
        return $this->belongsTo('Modules\Unit\Unit');
    }
}
