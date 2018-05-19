<?php

namespace Denmasyarikin\Production\Service\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServicePrice;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;

class ServiceCetakDigital extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $serviceCetakId = 4;

        ServiceType::create([
            'id' => 5,
            'name' => 'Banner',
            'service_id' => $serviceCetakId,
            'unit_id' => 33,
            'min_order' => 1,
            'order_multiples' => 1,
            'enabled' => 0,
        ]);

        ServicePrice::create(['service_type_id' => 5, 'price' => 30000]);

        ServiceTypeConfiguration::create([
            'name' => 'Area Cetak',
            'service_type_id' => 5,
            'type' => 'multiplication_area',
            'configuration' => [
                'relativity' => 'unit_price',
                'min_width' => 1,
                'max_width' => 5,
                'min_length' => 1,
                'max_length' => 100,
                'default_width' => 1,
                'default_length' => 1,
            ],
        ]);
    }
}
