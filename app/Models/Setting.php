<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    public $table = 'settings';
    
    public $fillable = [
        'device_types',
    ];
    
    protected $casts = [
        'device_types' => 'json',
    ];
    
    static public function deviceTypes()
    {
        return self::first()->device_types;
    }
}
