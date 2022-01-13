<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends BaseModel
{
    use HasFactory;
    
    public $table = 'purchase_orders';

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'store_id',
        'user_id',
        'order_no',
        'total_quantity',
        'total_price',
        'logistic_id',
        'waybill_number',
        'status',
        'items',
        'comment'
    ];
    
    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer', 
        'order_no' => 'string',
        'total_quantity' => 'float',
        'total_price' => 'float',
        'logistic_id' => 'integer',
        'waybill_number' => 'string',
        'status' => 'string',
        'items' => 'array',
        'comment' => 'string'
    ];
    
    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            if (!$instance->order_no){
                $instance->order_no = new_order_no();
            }
        });
        static::updating(function($instance) {
        });
    }       
    const PURCHASING = 'purchasing';
    const SHIPPED = 'shipped';
    const IMPORTED = 'imported';    
    const CANCELED = 'canceled';
    
    static public function statusOptions()
    {
        return [
            self::PURCHASING    => __(ucfirst(self::PURCHASING)),
            self::SHIPPED       => __(ucfirst(self::SHIPPED)),
            self::IMPORTED      => __(ucfirst(self::IMPORTED)),
            self::CANCELED      => __(ucfirst(self::CANCELED)),
        ];
    }
    
    public function statusLabel()
    {
        return self::statusOptions()[$this->status];
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function logisticProgress()
    {
        return $this->morphOne(LogisticProgress::class, 'order');
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }    
    
    public function detail()
    {
        return $this->info();
    }        
}
