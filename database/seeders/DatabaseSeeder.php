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
        $this->call(CategorySeeder::class);
        $this->call(GoodsSeeder::class);
        // SyncHelper::categories();
        // SyncHelper::goods();
        foreach (\App\Category::all() as $cat) {
            $url = 'category/'.$cat->name . ".jpeg";
            if (file_exists(\Storage::disk('public')->path($url))) {
                $cat->update(['image' => $url]);
                echo "update $cat->name image $url\n";
            }
        }
        $this->call(StoreSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(CartSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
    }
}
