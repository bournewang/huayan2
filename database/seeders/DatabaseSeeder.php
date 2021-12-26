<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Helpers\SyncHelper;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            GoodsSeeder::class,
            SupplierSeeder::class,
            StoreSeeder::class,
            BannerSeeder::class,
            UserSeeder::class,
            AreaSeeder::class,
            AddressSeeder::class,
            CartSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
