<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $store = \App\Store::create([
            'name' => '小菊',
            'company_name' => '上海小菊科技有限公司',
            'account_no' => '11112222333',
            'license_no' => 'GMA33112234433',
            'contact_name' => '王先生',
            'telphone' => '13811112222',
            // 'commission' => 30
        ]);
    }
}
