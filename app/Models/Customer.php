<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    public $table = 'users';
    
    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            $instance->type = User::CUSTOMER;
        });
    }
}