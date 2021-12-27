<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            'ggz17zg9nAJ' => ['Graphene_G0001', 'Graphene_G0002', 'Graphene_G0003'],
            'ggz1JU0WNFH' => ['JQR_0003', 'JQR_0004', 'JQR_0005']
        ];
        foreach ($data as $key => $names) {
            foreach ($names as $name) {
                \App\Models\Device::create([
                    'store_id' => \App\Models\Store::first()->id,
                    'product_key' => $key,
                    'device_name' => $name,
                    'status' => 1
                ]);
            }
        }
    }
}
