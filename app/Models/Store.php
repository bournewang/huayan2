<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class Store extends BaseModel
{
    use SoftDeletes;
    use StatusTrait;
    use AddressTrait;
    
    public $table = 'stores';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        // 'id',
        'name',
        'company_name',
        'license_no',
        'account_no', 
        'contact',
        'telephone',
        'province_id',
        'city_id',
        'district_id',
        'street',
        'status',
        'manager_id',
        'salesman_id',
        // 'commission'
    ];
    
    protected $casts = [
        'name' => 'string',
        'company_name' => 'string',
        'license_no' => 'string',
        'account_no' => 'string', 
        'contact' => 'string',
        'telephone' => 'string',
        // 'license_img' => 'string',
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
    

    
    // protected static function beforesave(&$instance)
    // {
    // }
    // 
    // public static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($instance) {
    //         self::beforesave($instance);
    //     });
    //     static::updating(function ($instance) {
    //         self::beforesave($instance);
    //     });
    // }
    
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
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function manager(){return $this->belongsTo(User::class);}
    public function salesman(){return $this->belongsTo(User::class);}
    public function devices(){return $this->hasMany(Device::class);}

}
