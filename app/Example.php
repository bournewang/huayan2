<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Example extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'id';
    
    public $table = 'examples';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
    ];
    
    protected $casts = [
        'name' => 'string',
    ];

    protected $hidden = [
        'id',
    ];
    
}


