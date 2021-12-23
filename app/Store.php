<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class Store extends BaseModel
{
    use SoftDeletes;
    
    // protected $primaryKey = 'id';
    
    public $table = 'stores';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'id',
        'name',
        'company_name',
        'license_no',
        'account_no', 
        'contact_name',
        'telphone',
        'license_img',
        'tier_bonus',
        'leader_bonus',
        'bonus_title',
        'width_bonus', 
        'depth_bonus',
        // 'commission'
    ];
    
    protected $casts = [
        'name' => 'string',
        'company_name' => 'string',
        'license_no' => 'string',
        'account_no' => 'string', 
        'contact_name' => 'string',
        'telphone' => 'string',
        'license_img' => 'string',
        'tier_bonus' => 'json',
        'leader_bonus' => 'json',
        'bonus_title' => 'json',
        'width_bonus' => 'json', 
        'depth_bonus' => 'json',
        // 'commission' => 'integer'
    ];

    public static $rules = [
        'name' => 'required|string',
        'company_name' => 'requried|string',
        'license_no' => 'required|string',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'shopId',
    ];
    
    protected static function beforesave(&$instance)
    {
        if ($instance->tier_bonus) {
            $arr = [];
            foreach ($instance->tier_bonus as $key => $val) {
                $arr[intval($key)] = intval($val);
            }
            $instance->tier_bonus = $arr;
        }
        if ($instance->bonus_title) {
            $arr = [];
            foreach ($instance->bonus_title as $key => $val) {
                $arr[intval($key)] = ($val);
            }
            $instance->bonus_title = $arr;
        }
    }
    
    // const $_default_params = ['width_bonus', 'width_pgpv', 'width_dd_qty', 'depth_bonus', 'depth_dd_qty', 'tier_bonus', 'bonus_title'];
    public static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            self::beforesave($instance);
            foreach (config('mall.store') as $key => $val) {
                $instance->$key = $instance->$key ?? config('mall.store.'.$key);
            }
        });
        static::updating(function ($instance) {
            self::beforesave($instance);
        });
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withPivot('recommend');
    }
    
    public function goods()
    {
        return $this->belongsToMany(Goods::class)->withPivot('recommend', 'hot');
    }
    
    public function flush()
    {
        flush_tag("store.$this->id");
    }
    
    public function users()
    {
        return $this->hasMany(User::class);//->withPivot('superior_id', 'level', 'sharing');
    }
    
    public function roots()
    {
        return $this->users()->whereNull('senior_id')->get();
    }
    
    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
    
    public function commission($goods)
    {
        return $goods->commission() ?? $this->commission;
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }
    
    public function minDD()
    {
        return last(array_keys($this->tier_bonus));
    }
    
    public function ratio($gpv)
    {
        $res = array_flip($this->tier_bonus);
        krsort($res);
        $ratio = 0;
        foreach ($res as $r => $amount) {
            if ($gpv >= $amount) {
                $ratio = $r;
                break;
            }
        }
        return $ratio;
    }
}
