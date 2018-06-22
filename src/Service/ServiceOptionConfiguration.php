<?php

namespace Denmasyarikin\Production\Service;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOptionConfiguration extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_service_option_configurations';

    /**
     * Get the serviceOption record associated with the ServiceOptionConfiguration.
     */
    public function serviceOption()
    {
        return $this->belongsTo(ServiceOption::class)->withTrashed();
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
