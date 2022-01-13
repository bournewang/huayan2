<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
class SalesOrder extends BaseModel
{
    use HasFactory;
    
    public $table = 'sales_orders';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'store_id',
        'user_id',
        'customer_id',
        'total_quantity',
        'total_price',
        'paid_price',
        'items',
        'comment'
    ];
    
    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer', 
        'customer_id' => 'integer',
        'total_quantity' => 'float',
        'total_price' => 'float',
        'paid_price' => 'float',
        'items' => 'array',
        'comment' => 'string',
    ];
        
    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
        });
        static::updating(function($instance) {
        });
    }        
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
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


