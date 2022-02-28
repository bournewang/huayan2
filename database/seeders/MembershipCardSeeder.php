<?php

namespace Database\Seeders;

use App\Models\MembershipCard;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MembershipCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        MembershipCard::create([
            'store_id' => 1,
            'user_id' => 4,
            'customer_id' => 5,
            'card_no' => '223344',
            'total_price' => 118.00,
            'paid_price' => 100,
            'validity_type' => MembershipCard::MONTH,
            'validity_period' => 3,
//            'validity_to' => ,
            'validity_start' => Carbon::today(),
            'status' => MembershipCard::VALID,
            'comment' => null
        ]);
    }
}
