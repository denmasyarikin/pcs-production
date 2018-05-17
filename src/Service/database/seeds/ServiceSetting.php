<?php

namespace Denmasyarikin\Production\Service\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Production\Service\ServiceType;
use Denmasyarikin\Production\Service\ServicePrice;
use Denmasyarikin\Production\Service\ServiceTypeConfiguration;

class ServiceSetting extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $serviceSettingId = 1;

        ServiceType::create([
            'id' => 1,
            'name' => 'Undangan',
            'service_id' => $serviceSettingId,
            'unit_id' => 33,
            'min_order' => 1,
            'order_multiples' => 1,
            'enabled' => 1
        ]);

        ServicePrice::create(['service_type_id' => 1, 'price' => 20000]);
        
        ServiceTypeConfiguration::create([
            'name' => 'Jenis Undagnan',
            'service_type_id' => 1,
            'type' => 'selection',
            'configuration' => [
                'value' => [
                    ['label' => 'Blanko', 'value' => 10000],
                    ['label' => 'BW', 'value' => 15000],
                    ['label' => 'Custome', 'value' => 30000]
                ],
                'multiple' => false,
                'affected_the_price' => true,
                'relativity' => 'unit_price',
                'rule' => 'fixed',
                'formula' => 'addition'
            ],
            'required' => 1
        ]);

        ServiceType::create([
            'id' => 2,
            'name' => 'Kartu Nama',
            'service_id' => $serviceSettingId,
            'unit_id' => 33,
            'min_order' => 1,
            'order_multiples' => 1,
            'enabled' => 1
        ]);

        ServicePrice::create(['service_type_id' => 2, 'price' => 30000]);

        ServiceTypeConfiguration::create([
            'name' => 'Jumlah Nama',
            'service_type_id' => 2,
            'type' => 'increasement',
            'configuration' => [
                'relativity' => 'unit_price',
                'multiples' => 1,
                'rule' => 'fixed',
                'value' => 1000
            ],
            'required' => 1
        ]);

        ServiceTypeConfiguration::create([
            'name' => 'Kesulitan',
            'service_type_id' => 2,
            'type' => 'selection',
            'configuration' => [
                'value' => [
                    ['label' => 'Huruf Asing', 'value' => 10000],
                    ['label' => 'Dengan Logo', 'value' => 20000]
                ],
                'multiple' => true,
                'affected_the_price' => true,
                'relativity' => 'unit_price',
                'rule' => 'fixed',
                'formula' => 'addition'
            ],
            'required' => 1
        ]);
    }
}
