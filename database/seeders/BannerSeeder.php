<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Goods;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // if ($data = (config('seed')['banner'] ?? null)) {
        //     foreach ($data as $item) {
        foreach(Goods::limit(5)->get() as $goods) {
                $c = \App\Banner::create(['goods_id' => $goods->id, 'store_id' => 1, 'status' => 1]);
                echo "create banner $c->title \n";
        //     }
        }
    }
}
