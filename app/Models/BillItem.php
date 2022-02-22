<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
class BillItem extends BaseModel
{
    use HasFactory;

    public $table = 'bill_items';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'store_id',
        'user_id',
        'order_type',
        'order_id',
        'year',
        'month',
        'period',
        'price',
        'share',
        'amount'
    ];

    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'order_type' => 'string',
        'order_id' => 'integer',
        'year' => 'integer',
        'month' => 'integer',
        'period' => 'integer',
        'price' => 'integer',
        'share' => 'integer',
        'amount' => 'float',
    ];

    protected $hidden = [
        'id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->morphTo();
    }

    static public function generate($order)
    {
        $day = $order->created_at->format('d');
        $period = 0;
        if ($day > 20) {
            $period = 3;
        }elseif($day > 10) {
            $period = 2;
        }else{
            $period = 1;
        }
        return self::create([
            'store_id' => $order->store_id,
            'user_id' => $order->user_id,
            'order_type' => get_class($order),
            'order_id' => $order->id,
            'year' => $order->created_at->format('Y'),
            'month' => $order->created_at->format('m'),
            'period' => $period,
            'price' => $order->amount ?? 0,
            'share' => 15,
            'amount' => round($order->amount * 15 / 100, 2)
        ]);
    }
}
