<?php

namespace Denmasyarikin\Production\Service\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\ServicePrice;
use Denmasyarikin\Production\Service\ServiceOptionConfiguration;

class ServiceCetak extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $serviceCetakId = 2;

        ServiceOption::create([
            'id' => 3,
            'name' => 'Mesin Toko',
            'service_id' => $serviceCetakId,
            'unit_id' => 33,
            'min_order' => 100,
            'order_multiples' => 10,
            'enabled' => 1,
        ]);

        ServicePrice::create(['service_option_id' => 3, 'price' => 15000]);

        ServiceOptionConfiguration::create([
            'name' => 'Harga Kelipatan',
            'sequence' => 1,
            'service_option_id' => 3,
            'type' => 'multiples',
            'structure' => [
                'relativity' => 'unit_price',
                'relativity_state' => 'initial',
                'input_multiples' => false,
                'input_min' => 0,
                'input_max' => 0,
                'input_default' => 0,
                'multiples' => 500,
                'after_quantity' => 7000,
                'rule' => 'fixed',
                'value' => 50,
            ],
        ]);

        ServiceOptionConfiguration::create([
            'name' => 'Penyesuaian',
            'sequence' => 2,
            'service_option_id' => 3,
            'type' => 'adjustment_quantity',
            'structure' => [
                'type' => 'discount',
                'relativity' => 'unit_total',
                'relativity_state' => 'calculated',
                'rule' => 'fixed',
                'value' => 1000,
                'quantity' => 1000,
                'operator' => '>',
            ],
        ]);

        ServiceOptionConfiguration::create([
            'name' => 'Jumlah Warna',
            'sequence' => 3,
            'service_option_id' => 3,
            'type' => 'multiplication',
            'structure' => [
                'relativity' => 'unit_total',
                'relativity_state' => 'calculated',
                'min' => 1,
                'max' => 20,
                'default' => 1,
            ],
        ]);

        ServiceOptionConfiguration::create([
            'name' => 'Arah Putar Cetak',
            'sequence' => 4,
            'service_option_id' => 3,
            'type' => 'selection',
            'structure' => [
                'value' => ['Bulak Bali Sama', 'Bulak Bali Bakul'],
                'multiple' => false,
                'required' => false,
                'default' => 'Bulak Bali Sama',
                'affected_the_price' => false,
                'relativity' => null,
                'relativity_state' => null,
                'rule' => null,
                'formula' => null,
            ],
        ]);
    }
}
