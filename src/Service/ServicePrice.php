<?php

namespace Denmasyarikin\Production\Service;

use App\Model;
use Modules\Chanel\Chanel;
use App\Manager\Contracts\Price;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePrice extends Model implements Price
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production_service_prices';

    /**
     * Get the chanel record associated with the ServicePrice.
     */
    public function chanel()
    {
        return $this->belongsTo(Chanel::class)->withTrashed();
    }

    /**
     * Get the serviceOption record associated with the ServicePrice.
     */
    public function serviceOption()
    {
        return $this->belongsTo(ServiceOption::class)->withTrashed();
    }

    /**
     * get priceabel.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getPriceabel()
    {
        return $this->serviceOption();
    }

    /**
     * Get the previous record associated with the ServicePrice.
     */
    public function previous()
    {
        return $this->belongsTo(static::class, 'previous_id')->withTrashed();
    }
}
