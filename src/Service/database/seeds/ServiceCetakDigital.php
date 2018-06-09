<?php

namespace Denmasyarikin\Production\Service\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\ServicePrice;
use Denmasyarikin\Production\Service\ServiceOptionConfiguration;

class ServiceCetakDigital extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $serviceCetakId = 4;

        ServiceOption::create([
            'id' => 5,
            'name' => 'Banner',
            'service_id' => $serviceCetakId,
            'unit_id' => 33,
            'min_order' => 1,
            'order_multiples' => 1,
            'enabled' => 0,
        ]);

        ServicePrice::create(['service_option_id' => 5, 'price' => 30000]);

        ServiceOptionConfiguration::create([
            'name' => 'Area Cetak',
            'sequence' => 1,
            'service_option_id' => 5,
            'type' => 'area',
            'structure' => [
                'relativity' => 'unit_price',
                'relativity_state' => 'initial',
                'min_width' => 1,
                'max_width' => 5,
                'min_length' => 1,
                'max_length' => 100,
                'default_width' => 1,
                'default_length' => 1,
            ],
        ]);

        ServiceOption::create([
            'id' => 6,
            'name' => 'A3',
            'service_id' => $serviceCetakId,
            'unit_id' => 16,
            'min_order' => 1,
            'order_multiples' => 1,
            'enabled' => 0,
        ]);

        ServicePrice::create(['service_option_id' => 6, 'price' => 3000]);
    }
}
