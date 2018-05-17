<?php

namespace Denmasyarikin\Production\Service;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceTypeConfiguration extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_service_type_configurations';

    /**
     * Get the serviceType record associated with the ServiceTypeConfiguration.
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get Configuration.
     *
     * @param string $value
     *
     * @return string
     */
    public function getConfigurationAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Set Configuration.
     *
     * @param string $value
     *
     * @return string
     */
    public function setConfigurationAttribute($value)
    {
        $this->attributes['configuration'] = json_encode($value);
    }
}
