<?php

namespace Denmasyarikin\Production;

use App\Manager\Contracts\Setting as SettingContract;

class Setting implements SettingContract
{
    /**
     * get setting.
     *
     * @return array
     */
    public function getSetting()
    {
        return [
            'tagline' => 'Sistem Produksi'
        ];
    }
}
