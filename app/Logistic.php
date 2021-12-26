<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistic extends Model
{
    use HasFactory;
    
    protected $table = 'logistics';
    
    protected $fillable = [
        'name', 'contact', 'telephone', 'address'
    ];
    
}
