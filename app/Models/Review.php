<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends BaseModel
{
    use HasFactory;
    public $table = 'reviews';

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'store_id',
        'user_id',
        'order_id',
        'rating',
        'comment'
    ];
    
    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'order_id' => 'integer',
        'rating' => 'string',
        'comment' => 'string',   
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
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
