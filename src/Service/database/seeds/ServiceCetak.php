<?php

namespace Denmasyarikin\Production\Service\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServicePrice;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;

class ServiceCetak extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $serviceCetakId = 2;

        ServiceType::create([
            'id' => 3,
            'name' => 'Mesin Toko',
            'service_id' => $serviceCetakId,
            'unit_id' => 33,
            'min_order' => 100,
            'order_multiples' => 10,
            'enabled' => 1
        ]);

        ServicePrice::create(['service_type_id' => 3, 'price' => 30000]);

        ServiceTypeConfiguration::create([
            'name' => 'Harga Kelipatan',
            'service_type_id' => 3,
            'type' => 'increasement',
            'configuration' => [
                'relativity' => 'unit_price',
                'multiples' => 500,
                'rule' => 'percentage',
                'value' => 50
            ],
            'required' => 1
        ]);

        ServiceTypeConfiguration::create([
            'name' => 'Jumlah Warna',
            'service_type_id' => 3,
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