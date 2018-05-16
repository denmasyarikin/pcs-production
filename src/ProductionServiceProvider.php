<?php

namespace Denmasyarikin\Production;

use App\Manager\Facades\Package;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class ProductionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Relation::morphMap(['service_type' => 'Denmasyarikin\Production\Service\ServiceType']);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        Package::register('production', __DIR__, 'Denmasyarikin\Production');
    }
}
