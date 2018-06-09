<?php

namespace Denmasyarikin\Production\Service\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Production\Service\ServiceOption;
use Denmasyarikin\Production\Service\ServicePrice;
use Denmasyarikin\Production\Service\ServiceOptionConfiguration;

class ServiceSetting extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $serviceSettingId = 1;

        ServiceOption::create([
            'id' => 1,
            'name' => 'Undangan',
            'service_id' => $serviceSettingId,
            'unit_id' => 33,
            'min_order' => 1,
            'order_multiples' => 1,
            'enabled' => 1,
        ]);

        ServicePrice::create(['service_option_id' => 1, 'price' => 20000]);

        ServiceOptionConfiguration::create([
            'name' => 'Jenis Undagnan',
            'sequence' => 1,
            'service_option_id' => 1,
            'type' => 'selection',
            'structure' => [
                'value' => [
                    ['label' => 'Blanko', 'value' => 10000],
                    ['label' => 'BW', 'value' => 15000],
                    ['label' => 'Custome', 'value' => 30000],
                ],
                'default' => ['label' => 'Blanko', 'value' => 10000],
                'multiple' => false,
                'required' => true,
                'affected_the_price' => true,
                'relativity' => 'unit_price',
                'relativity_state' => 'initial',
                'rule' => 'fixed',
                'formula' => 'addition',
            ],
        ]);

        ServiceOption::create([
            'id' => 2,
            'name' => 'Kartu Nama',
            'service_id' => $serviceSettingId,
            'unit_id' => 33,
            'min_order' => 1,
            'order_multiples' => 1,
            'enabled' => 1,
        ]);

        ServicePrice::create(['service_option_id' => 2, 'price' => 30000]);
        
        ServiceOptionConfiguration::create([
            'name' => 'Kesulitan',
            'sequence' => 1,
            'service_option_id' => 2,
            'type' => 'selection',
            'structure' => [
                'value' => [
                    ['label' => 'Huruf Asing', 'value' => 10000],
                    ['label' => 'Dengan Logo', 'value' => 20000],
                ],
                'default' => [],
                'multiple' => true,
                'required' => false,
                'affected_the_price' => true,
                'relativity' => 'unit_price',
                'relativity_state' => 'initial',
                'rule' => 'fixed',
                'formula' => 'addition',
            ],
        ]);

        ServiceOptionConfiguration::create([
            'name' => 'Jumlah Nama',
            'sequence' => 2,
            'service_option_id' => 2,
            'type' => 'multiples',
            'structure' => [
                'relativity' => 'unit_price',
                'relativity_state' => 'calculated',
                'multiples' => 1,
                'input_multiples' => true,
                'input_min' => 1,
                'input_max' => 1000,
                'input_default' => 1,
                'rule' => 'fixed',
                'value' => 1000,
            ],
        ]);
    }
}
