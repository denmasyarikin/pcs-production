<?php

namespace Denmasyarikin\Production\Service\database\seeds;

use Illuminate\Database\Seeder;
use Modules\Workspace\Workspace;
use Denmasyarikin\Production\Service\Service;

class ServiceWorkspace extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach (Workspace::get() as $workspace) {
            foreach (Service::get() as $service) {
                $service->workspaces()->attach($workspace);
            }
        }
    }
}
