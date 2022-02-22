<?php

namespace App\Console\Commands;

use App\Models\BillItem;
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

        foreach (Order::whereBetween('paid_at', [$start, $end])->get() as $order){
            if (!$order->billItems->first())
                BillItem::generate($order);
            echo "generate for order $order->id\n";
        }
        foreach (SalesOrder::whereBetween('created_at', [$start, $end])->get() as $order){
            if (!$order->billItems->first())
                BillItem::generate($order);
            echo "generate for sales order $order->id\n";
        }
        foreach (ServiceOrder::whereBetween('created_at', [$start, $end])->get() as $order){
            if (!$order->billItems->first())
                BillItem::generate($order);
            echo "generate for service order $order->id\n";
        }
        return 0;
    }
}
