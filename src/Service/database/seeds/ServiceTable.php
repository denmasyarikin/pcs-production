<?php

namespace Denmasyarikin\Production\Service\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Production\Service\Service;

class ServiceTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Service::create([
            'id' => 1,
            'name' => 'Setting',
            'description' => 'Design Digital untuk kebutuhan cetak',
            'status' => 'active',
        ]);

        Service::create([
            'id' => 2,
            'name' => 'Ongkos Cetak',
            'description' => 'Biaya cetak berbagai mesin dan media',
            'status' => 'active',
        ]);

        Service::create([
            'id' => 3,
            'name' => 'Finishing',
            'description' => 'Penyelesaian hasil akhir percetakan',
            'status' => 'active',
        ]);

        Service::create([
            'id' => 4,
            'name' => 'Print Digital',
            'description' => 'Cetak dengan mesin digital',
            'status' => 'active',
        ]);
    }
}
