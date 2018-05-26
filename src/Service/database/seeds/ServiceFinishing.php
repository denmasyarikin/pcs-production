<?php

namespace Denmasyarikin\Production\Service\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\ServicePrice;
use Denmasyarikin\Production\Service\ServiceOptionConfiguration;

class ServiceFinishing extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $serviceFinishingId = 3;

        ServiceOption::create([
            'id' => 4,
            'name' => 'Numertator',
            'service_id' => $serviceFinishingId,
            'unit_id' => 24,
            'min_order' => 500,
            'order_multiples' => 100,
            'enabled' => 1,
        ]);

        ServicePrice::create(['service_option_id' => 4, 'price' => 20000]);

        ServiceOptionConfiguration::create([
            'name' => 'Harga Kelipatan',
            'service_option_id' => 4,
            'type' => 'multiples',
            'structure' => [
                'relativity' => 'unit_price',
                'multiples' => 500,
                'input_multiples' => false,
                'input_min' => 0,
                'input_max' => 0,
                'input_default' => 0,
                'rule' => 'percentage',
                'value' => 100,
            ],
        ]);

        ServiceOptionConfiguration::create([
            'name' => 'Jumlah Titik',
            'service_option_id' => 4,
            'type' => 'multiplication',
            'structure' => [
                'relativity' => 'unit_total',
                'min' => 1,
                'max' => 20,
                'default' => 1,
            ],
        ]);
    }
}
