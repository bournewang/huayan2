<?php

namespace App\Console\Commands;

use App\Models\BillItem;
use App\Models\MembershipCard;
use App\Models\Order;
use App\Models\SalesOrder;
use App\Models\ServiceOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateBillItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:bill-items {day?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate bill items of a given day(by default yesterday)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$day = $this->argument('day')) {
            $day = 'yesterday';
        }
        echo "day:$day\n";
        $start = Carbon::parse($day);
        $end = Carbon::parse($day)->addHours(23)->addMinutes(59)->addSeconds(59);
        echo "start $start, end $end\n";
        $order_types = [
            Order::class => 'paid_at',
            SalesOrder::class => 'created_at',
            ServiceOrder::class => 'created_at',
            MembershipCard::class => 'validity_to'
        ];
        foreach ($order_types as $class => $field) {
            echo "process $class\n";
            foreach ($class::whereBetween($field, [$start, $end])->get() as $order){
                if (!$order->billItems->first())
                    BillItem::generate($order, $field);
                echo "    generate for order $order->id\n";
            }
        }

        return 0;
    }
}
