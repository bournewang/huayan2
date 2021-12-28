<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\Setting::create([
            'device_types' => [
                'ggz1JU0WNFH' => '强筋机器人',
                'ggz17zg9nAJ' => '石墨烯能量房',
                'ggz1Vk6qGPr' => '超长波治疗仪'
            ]
        ]);
    }
}
