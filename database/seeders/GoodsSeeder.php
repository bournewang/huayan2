<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Goods;

class GoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if ($data = (config('seed')['goods'] ?? null)) {
            foreach ($data as $item) {
                $c = Goods::create($item);
                echo "create goods $c->name \n";
            }
        }
    }
}
