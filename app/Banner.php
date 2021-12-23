<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends BaseModel
{
    //
    use SoftDeletes;

    protected $primaryKey = 'id';
    
    public $table = 'banners';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'store_id',
        'goods_id',
        'title',
        'link',
        'image',
        'status'
    ];    
    
    protected $casts = [
        'store_id' => 'integer',
        'goods_id' => 'integer',
        'title' => 'string',
        'link' => 'string',
        'image' => 'string',
        'status' => 'string',
    ];
    
    public static $rules = [
        // 'store_id' => 'integer|required',
        // 'title' => 'string|required',
        // 'image' => 'string|required',
        // 'status' => 'string|required',
    ];
    
    
    protected static function beforesave(&$instance)
    {
        if ($instance->goods_id) {
            $instance->link = $instance->link ?? "/pages/goods-details/index?id=$instance->goods_id";
            $instance->image = $instance->image ?? $instance->goods->img;
            $instance->title = $instance->title ?? $instance->goods->name;
        }
        $instance->store_id = $instance->store_id ?? \Auth::user()->store_id;
    }
    
    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            self::beforesave($instance);
        });
        static::updating(function ($instance) {
            self::beforesave($instance);
        });
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
    
    public function show()
    {
        return [
            "businessId" => "",
            "dateAdd" => "",
            "dateUpdate" => "",
            "id" => $this->id,
            "linkUrl" => $this->link,
            "paixu" => "",
            "picUrl" => !$this->image ? null : url(\Storage::url($this->image)),
            "status" => $this->status,
            "statusStr" => $this->title,
            "title" => $this->title,
            "type" => "",
            "userId" => "",
        ];
    }
    
}
