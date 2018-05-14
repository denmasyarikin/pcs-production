<?php

namespace Denmasyarikin\Production;

use App\Manager\Facades\Package;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ProductionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        Package::register('production', __DIR__, 'Denmasyarikin\Production');
    }
}
