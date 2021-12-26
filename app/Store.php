<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class Store extends BaseModel
{
    use SoftDeletes;
    use StatusTrait;
    
    public $table = 'stores';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        // 'id',
        'name',
        'company_name',
        'license_no',
        'account_no', 
        'contact_name',
        'telphone',
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
        'contact_name' => 'string',
        'telphone' => 'string',
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

    public function province(){return $this->belongsTo(Province::class);}
    public function city(){return $this->belongsTo(City::class);}
    public function district(){return $this->belongsTo(District::class);}
    public function manager(){return $this->belongsTo(User::class);}
    public function salesman(){return $this->belongsTo(User::class);}

    public function display_address($with_telephone = false)
    {
        return implode(array_filter([
            $this->province->name ?? null,
            $this->city->name ?? null,
            $this->district->name ?? null,
            $this->street,
            $with_telephone ? ' '.$this->telephone : '',
        ]));
    }
}
