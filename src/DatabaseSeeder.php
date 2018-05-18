<?php

namespace Denmasyarikin\Production;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call(Service\database\seeds\ServiceTable::class);
        $this->call(Service\database\seeds\ServiceSetting::class);
        $this->call(Service\database\seeds\ServiceCetak::class);
        $this->call(Service\database\seeds\ServiceFinishing::class);
        $this->call(Service\database\seeds\ServiceCetakDigital::class);
    }
}
