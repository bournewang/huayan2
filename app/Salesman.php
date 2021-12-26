<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salesman extends User
{
    use HasFactory;
    
    protected $table = 'users';
}
