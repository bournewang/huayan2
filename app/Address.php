<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Address extends BaseModel
{
    //
    use SoftDeletes;

    protected $primaryKey = 'id';
    
    public $table = 'addresses';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'consignee',
        'telephone',
        'province_id',
        'city_id',
        'district_id',
        // 'county',
        'street',
        'default'
    ];    
    
    protected $casts = [
        'user_id' => 'integer',
        'consignee' => 'string',
        'telephone' => 'string',
        'province_id' => 'integer',
        'city_id' => 'integer',
        'district_id' => 'integer',
        // 'province' => 'string',
        // 'city' => 'string',
        // 'county' => 'string',
        'street' => 'string',
        'default' => 'boolean'
    ];
    
    public static $rules = [
        'store_id' => 'integer|required',
        'user_id' => 'integer|required',
        'consignee' => 'string|required|max:12',
        'telephone' => 'string|required|max:24',
        'province' => 'string|required|max:32',
        'city' => 'string|required|max:32',
        'county' => 'string|required|max:32',
        'street' => 'string',
    ];
    
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    
    public function toString()
    {
        return  implode('', array_filter([$this->province->name??null, $this->city->name??null, $this->district->name??null, $this->street])); 
                // ' '.implode(' ', [$this->consignee, $this->telephone]);
    }
    
    public function detail()
    {
        $info = $this->info();
        $info['address'] = $this->toString();
        return $info;
    }
}

