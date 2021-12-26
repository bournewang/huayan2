<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends BaseModel
{
    //
    use SoftDeletes;
    use ShelfTrait;

    protected $primaryKey = 'id';
    
    public $table = 'categories';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'pid',
        'id',
        'name',
        'status'
    ];    
    
    protected $casts = [
        'pid' => 'integer',
        'id' => 'integer',
        'name' => 'string',
    ];
    
    public static $rules = [
        'id' => 'required',
        'name' => 'requried',
    ];
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'pid');
    }
    
    public function children()
    {
        return $this->hasMany(Category::class, 'pid');
    }
    
    public function goods()
    {
        return $this->hasMany(Goods::class);
    }
    
    public function show()
    {
        return [
            "id" =>  $this->id,
            "name" =>  $this->name,
            "pid" => $this->pid,
        ];
    }
}
