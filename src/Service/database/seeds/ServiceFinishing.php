<?php

namespace Denmasyarikin\Production\Service\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServicePrice;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;

class ServiceFinishing extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $serviceFinishingId = 3;

        ServiceType::create([
            'id' => 4,
            'name' => 'Numertator',
            'service_id' => $serviceFinishingId,
            'unit_id' => 24,
            'min_order' => 500,
            'order_multiples' => 100,
            'enabled' => 1
        ]);

        ServicePrice::create(['service_type_id' => 4, 'price' => 20000]);
        
        ServiceTypeConfiguration::create([
            'name' => 'Jumlah Titik',
            'service_type_id' => 4,
            'type' => 'multiplication',
            'configuration' => [
                'relativity' => 'unit_total',
                'min' => 1,
                'max' => 20,
                'default' => 1
            ],
            'required' => 1
        ]);
    }
}
