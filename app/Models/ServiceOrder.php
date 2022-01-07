<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;
    
    public $table = 'service_orders';

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'store_id',
        'user_id',
        'device_id',
        'order_no',
        'title',
        'detail',
        'amount',
        'raw_data',
    ];
    
    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'device_id' => 'integer',
        'order_no' => 'string',
        'title' => 'string',
        'detail' => 'string',
        'amount' => 'float',
        'raw_data' => 'json', 
    ];
    
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }    
}
