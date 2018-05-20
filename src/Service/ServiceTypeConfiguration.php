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
     * Get Structure.
     *
     * @param string $value
     *
     * @return string
     */
    public function getStructureAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Set Structure.
     *
     * @param string $value
     *
     * @return string
     */
    public function setStructureAttribute($value)
    {
        $this->attributes['structure'] = json_encode($value);
    }
}
