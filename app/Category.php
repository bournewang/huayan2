<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends BaseModel
{
    //
    use SoftDeletes;

    protected $primaryKey = 'id';
    
    public $table = 'categories';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'pid',
        'id',
        'name',
        'image',
        'commission'
    ];    
    
    protected $casts = [
        'pid' => 'integer',
        'id' => 'integer',
        'name' => 'string',
        'image' => 'string',
        'commission' => 'integer'
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
    
    public function stores()
    {
        return $this->belongsToMany(Store::class)->withPivot('recommend');
    }
    
    public function show()
    {
        return [
            "icon" => storage_url($this->image),
            "id" =>  $this->id,
            "isUse" =>  true,
            "key" =>  "1",
            "level" =>  1,
            "name" =>  $this->name,
            "paixu" =>  0,
            "pid" => $this->pid,
            "shopId" => 0,
            "type" => "index",
            "userId" => null,
            "vopCid1" =>  null,
            "vopCid2" =>  null
        ];
    }
}
